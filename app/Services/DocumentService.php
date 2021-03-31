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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Request;


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
            ->where('object_id','=',$id)
            ->get();
        return $documents;
    }

}
