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
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Writer_Excel2007;
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
    public static function showAll(): array
    {
        return Document::query()->get()->all();
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

    static public function generateXLS(ObjectB $object)
    {
        $data = ObjectService::show($object);
        return self::generateObjectSummary($data);
    }

    static public function generateObjectSummary($data)
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

    static public function archivateDocs()
    {
        $name = '/back_up_' . date('yyyymmdd') . '.zip';
        $filename = storage_path() . '/app/public/back_ups' . $name;
        $path = storage_path() . '/app/public/documents';
        $files = array_diff(scandir($path), ['..', '.']);
        ZipArchiveService::addArchive($filename, $path, $files);
        return response()->download(storage_path('app/public/back_ups' . $name));
    }

}
