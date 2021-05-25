<?php


namespace App\Services;


use App\Exceptions\API\ValidateDoc;
use App\Models\Document;
use App\Models\ObjectB;
use Exception;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Writer_Excel2007;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Request;
use function GuzzleHttp\Psr7\str;


class DocumentService
{
    /**
     * @param Request $request
     * @return Document|MessageBag|array|false|Builder|Model|JsonResponse|\Illuminate\Support\MessageBag|string
     */
    public static function Create(Request $request)
    {
        $input_data = $request->all();
        ValidateDoc::validateDocument($input_data);

        if (!isset($request->file)) {
            return json_decode(response()->json(['errors' => "Введите корректное название(file[...])"], 400)->getContent());
        }
        if (count($request->all()) != 1) {
            $validate_count = ValidateDoc::customCountValidation(count($request->file));
            if ($validate_count != null) {
                return $validate_count;
            }
        }

        $result = [];
        ObjectB::query()->findOrFail($request->object_id);
        echo 12;
        foreach ($request->file as $file) {
            $path = $file->store('documents', 'public');
            $created_file = Document::query()->create([
                'name' => $file->getClientOriginalName(),
                'object_id' => $request->object_id,
                'path' => $path,
                'mime' => $file->getMimeType(),
                'size' => $file->getSize()]);
            array_push($result, $created_file);
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function showAll($request): array
    {
        ValidateDoc::validateSearch($request->all());

        $documents = Document::query()->select()->all();
        if ($request->searchString != "") {
            $documents = $documents->where('name', 'LIKE', '%' . $request->searchString . '%')->all();
        }
        if ($request->startDate != '') {
            $documents = $documents->where('created_at', '>', $request->startDate)->gall();
        }
        if ($request->endDate != '') {
            $documents = $documents->where('created_at', '<', $request->endDate)->all();
        }
        if ($request->documentType != '') {
            $documents = $documents->where('mime', '=', $request->documentType)->all();
        }
        return $documents;
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public static function showOne($id)
    {
        return Document::query()->findOrFail($id);
    }

    /**
     * @param $id
     * @return BinaryFileResponse
     */
    public static function downloadDoc($id): BinaryFileResponse
    {
        $file = self::showOne($id);
        $file = $file['path'];
        return response()->download(storage_path('app/public/' . $file));
    }

    /**
     * @param $id
     * @return string
     * @throws Exception
     */
    public static function delete($id): string
    {
        $file = self::showOne($id);
        $file->delete();
        return 'Ok';
    }


    static public function objects_document($id)
    {
        $documents = Document::query()
            ->where('object_id', '=', $id)
            ->get();
        return $documents;
    }

    static public function generateObjectSummary(ObjectB $object, $type)
    {
        $data = ObjectService::show($object);
        switch ($type) {
            case 'xlsx':
                return self::generateXLSX($data);
                break;
            case 'docx':
                return self::generateWord($data);
                break;
            default:
                echo "Неверно переданы данные!";
        }
    }

    static public function generateXLSX($data)
    {
        $xls = new PHPExcel();

        try {
            $xls->setActiveSheetIndex(0);
        } catch (\PHPExcel_Exception $e) {
            return $e->getMessage();
        }
        try {
            $sheet = $xls->getActiveSheet();
        } catch (\PHPExcel_Exception $e) {
            return $e->getMessage();
        }

        $sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::SETPRINTRANGE_OVERWRITE);

        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(17);
        $sheet->getColumnDimension('D')->setWidth(17);
        $sheet->getColumnDimension('E')->setWidth(10);

        $line = 1;
        $sheet->setCellValue("A{$line}", 'Сводка по объекту за ' . date('d.m.Y H:i'));
        $sheet->mergeCells("A{$line}:E{$line}");
        $sheet->getStyle("A{$line}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$line}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->getStyle("A{$line}")->getFont()->setBold(true);
        $sheet->getStyle("A{$line}")->getFont()->setSize(18);

        $line++;
        $sheet->setCellValue("A{$line}", '');
        $sheet->mergeCells("A{$line}:E{$line}");

        $line++;
        self::createHedderLine($sheet, $line, 'Адрес:', $data['address']);
        $line++;
        self::createHedderLine($sheet, $line, 'Площадь:', $data['area']);
        $line++;
        self::createHedderLine($sheet, $line, 'Количество комнат:', $data['amountRoom']);
        $line++;
        self::createHedderLine($sheet, $line, 'Дата начала работ:', $data['date_start']);
        $line++;
        self::createHedderLine($sheet, $line, 'Дата завершения работ:', $data['date_end']);
        $line++;
        self::createHedderLine($sheet, $line, 'Описание:', $data['description']);
        self::setBoldWrapLine($sheet, $line);


        $line = $line + 2;
        $start_table = $line;
        $sheet->setCellValue("A{$line}", '№ этапа');
        $sheet->setCellValue("B{$line}", 'Наименование');
        $sheet->mergeCells("B{$line}:D{$line}");
        $sheet->setCellValue("E{$line}", 'Статус');
        self::setBoldWrapLine($sheet, $line);

        $line++;
        $sheet->setCellValue("A{$line}", '№ работы');
        $sheet->setCellValue("B{$line}", 'Наименование');
        $sheet->setCellValue("C{$line}", 'Дата начала');
        $sheet->setCellValue("D{$line}", 'Дата завершения');
        $sheet->setCellValue("E{$line}", 'Статус');
        self::setBoldWrapLine($sheet, $line);

        foreach ($data['stages'] as $datum) {
            $line++;
            $sheet->setCellValue("A{$line}", (string)$datum['stage']['step_number']);
            $sheet->setCellValue("B{$line}", (string)$datum['stage']['stage_name']);
            $sheet->setCellValue("C{$line}", (string)$datum['stage']['name']);
            $sheet->setCellValue("E{$line}", (string)$datum['stage']['status_name']);
            // стили
            $sheet->getStyle("A{$line}:E{$line}")
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FFD7D7D7');
            $sheet->getStyle("A{$line}:G{$line}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach ($datum['works'] as $work) {
                $line++;
                $sheet->setCellValue("A{$line}", (string)$work['step_number']);
                $sheet->setCellValue("B{$line}", (string)$work['work_name']);
                $sheet->setCellValue("C{$line}", (string)$work['date_start']);
                $sheet->setCellValue("D{$line}", (string)$work['date_end']);
                $sheet->setCellValue("E{$line}", (string)($work['check']) ? 'Завершен' : 'В работе');
                $sheet->getStyle("A{$line}:G{$line}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }

        }

        $sheet->getStyle("A{$start_table}:E{$line}")->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $objWriter = new PHPExcel_Writer_Excel2007($xls);
        $name = date('dmYHi') . '_' . 'Сводка по объекту';
        $path = realpath('../storage/app/public/documents') . '/' . $name . '.xlsx';

        $objWriter->save($path);
        $size = filesize($path);
        $mime = mime_content_type($path);
        $created_file = Document::query()->create([
            'name' => $name,
            'object_id' => $data['object_id'],
            'path' => 'documents/' . md5($name),
            'mime' => '.xlsx',
            'size' => $size
        ]);
        return $created_file;

    }

    static public function createHedderLine($sheet, int $line, string $hedder = '', string $content = '')
    {
        $sheet->setCellValue("A{$line}", $hedder);
        $sheet->setCellValue("B{$line}", $content);
        $sheet->getStyle("B{$line}")->getFont()->setBold(true);
        $sheet->getStyle("A{$line}:E{$line}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("A{$line}:E{$line}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->mergeCells("B{$line}:E{$line}");
    }

    static public function setBoldWrapLine($sheet, $line)
    {
        $sheet->getStyle("A{$line}:G{$line}")->getFont()->setBold(true);
        $sheet->getStyle("A{$line}:G{$line}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("A{$line}:G{$line}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("A{$line}:G{$line}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    }

    static public function backupDocs()
    {
        $name = '/back_up_' . date('yyyymmdd') . '.zip';
        $path_archive = storage_path() . '/app/public/back_ups';
        $filename = $path_archive . $name;
        $path = storage_path() . '/app/public/documents';
        $files = array_diff(scandir($path), ['..', '.']);

        ZipArchiveService::addArchive($filename, $path, $files);

        $success = GoogleDriveService::insert_file_to_drive($filename, $name);

        if ($success) {
            echo "file uploaded successfully";
        } else {
            echo "Something went wrong.";
        }

        return response()->download(storage_path('app/public/back_ups' . $name));
    }

    static public function generateWord($data)
    {

        $phpWord = new  PhpWord();

        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

        $properties = $phpWord->getDocInfo();

        $properties->setCreator('System');
        $properties->setCompany('Констант-с');
        $properties->setCreated(date('d.m.y'));
        $properties->setModified(date('d.m.y'));

        $sectionStyle = array(

            'orientation' => 'portrait',
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::pixelToTwip(10),
            'marginLeft' => 600,
            'marginRight' => 600,
            'colsNum' => 1,
            'pageNumberingStart' => 1,
            'borderBottomSize' => 100,
            'borderBottomColor' => 'C0C0C0'

        );
        $section = $phpWord->addSection($sectionStyle);


        $text = "Сводка по активным объектам.";
        $fontStyle = array('name' => 'Arial', 'size' => 24, 'bold' => TRUE);
        $parStyle = array('align' => 'justify', 'spaceBefore' => 1.25);
        $section->addText(htmlspecialchars($text), $fontStyle, $parStyle);


        self::WordTextBlock($section);
        self::WordTextBlock($section, 'Адрес:', $data['address']);
        self::WordTextBlock($section, 'Площадь:', $data['area']);
        self::WordTextBlock($section, 'Количество комнат:', $data['amountRoom']);
        self::WordTextBlock($section, 'Дата начала работ:', $data['date_start']);
        self::WordTextBlock($section, 'Дата завершения работ:', $data['date_end']);
        self::WordTextBlock($section, 'Описание:', $data['description']);

        $cellRowSpan = array('vMerge' => 'restart');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan = array('gridSpan' => 2);
        $section->addTextBreak(1); // перенос строки
        $section->addText("Table with colspan and rowspan");

        $styleTable = array('borderSize' => 6, 'borderColor' => '999999');
        $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan2 = array('gridSpan' => 2, 'valign' => 'center');
        $cellColSpan3 = array('gridSpan' => 3, 'valign' => 'center');

        $cellHCentered = array('align' => 'center');
        $cellVCentered = array('valign' => 'center');

        $phpWord->addTableStyle('Colspan Rowspan', $styleTable);
        $table = $section->addTable('Colspan Rowspan');

        $table->addCell(2000, $cellColSpan3)->addText(' colspan=3 '
            . '(need enough columns under -- one diff from html)', null, $cellHCentered);
        $table->addCell(2000, $cellVCentered)->addText('E', null, $cellHCentered);
        $table->addCell(2000, $cellVCentered)->addText('F', null, $cellHCentered);

        foreach ($data['stages'] as $datum) {
            $table->addRow();
            $table->addCell(2000, $cellVCentered)->addText((string)$datum['stage']['step_number'], null, $cellHCentered);
            $table->addCell(2000, $cellVCentered)->addText((string)$datum['stage']['stage_name'], null, $cellHCentered);
            $table->addCell(2000, $cellColSpan2)->addText((string)$datum['stage']['name'], null, $cellHCentered);
            $table->addCell(2000, $cellVCentered)->addText((string)$datum['stage']['status_name'], null, $cellHCentered);

            foreach ($datum['works'] as $work) {
                $table->addRow();
                $table->addCell(2000, $cellVCentered)->addText((string)$work['step_number'], null, $cellHCentered);
                $table->addCell(2000, $cellVCentered)->addText((string)$work['work_name'], null, $cellHCentered);
                $table->addCell(2000, $cellVCentered)->addText((string)$work['date_start'], null, $cellHCentered);
                $table->addCell(2000, $cellVCentered)->addText((string)$work['date_end'], null, $cellHCentered);
                $table->addCell(2000, $cellVCentered)->addText((string)($work['check']) ? 'Завершен' : 'В работе', null, $cellHCentered);
            }

        }


        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        //$objWriter->save('doc.docx');

        $name = date('dmYHi') . '_' . 'сводка по активным объектам';
        $path = storage_path() . '/app/public/documents' . '/' . $name . '.docx';

        $objWriter->save($path);
        $size = filesize($path);
        $mime = mime_content_type($path);
        $created_file = Document::query()->create([
            'name' => $name,
            'object_id' => 1,
            'path' => 'documents/' . md5($name),
            'mime' => '.xlsx',
            'size' => $size
        ]);
        return response()->download(storage_path('/app/public/documents' . '/' . $name . '.docx'));
    }

    /**
     * @param null $head
     * @param null $data
     * @param $section
     */
    public static function WordTextBlock($section, $head = null, $data = null)
    {
        $text = "{$head}\t{$data}";
        $fontStyle = array('name' => 'Arial', 'size' => 14, 'bold' => TRUE);
        $parStyle = array('align' => 'left', 'spaceBefore' => 1.25);

        $section->addText(htmlspecialchars($text), $fontStyle, $parStyle);
    }
}
