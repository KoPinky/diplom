<?php


namespace App\Http\Controllers;

use App\Models\ObjectB;
use App\Services\DocumentService;
use App\Services\GoogleDriveService;
use App\Services\ObjectService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function docCreate(Request $request): JsonResponse
    {
        $document = DocumentService::Create($request);
        return response()->json($document);
    }

    /**
     * @return JsonResponse
     */
    public function docsShow($request): JsonResponse
    {
        return response()->json(DocumentService::showAll($request));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function docShow(int $id): JsonResponse
    {
        return response()->json(DocumentService::showOne($id));
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function docDownload(int $id)
    {
        return DocumentService::downloadDoc($id);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function docDelete(int $id): JsonResponse
    {
        $file = DocumentService::delete($id);
        return response()->json($file);
    }


    public function object_documents($id)
    {
        return response()->json(DocumentService::objects_document($id));
    }

    public function generateObjectSummary(ObjectB $object, $type)
    {
        return DocumentService::generateObjectSummary($object, $type);
    }


    public function archivateDocs()
    {
        return DocumentService::generateObjectSummary();
    }


}
