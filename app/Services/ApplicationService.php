<?php

namespace App\Services;

use App\Exceptions\API\ValidateApplication;
use App\Exceptions\API\ValidateDoc;
use App\Models\Application;
use App\Models\ApplicationList;
use App\Models\Document;
use App\Models\ObjectB;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationService
{

    private static function CreateDocument(Request $request)
    {
        $input_data = $request->all();
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
        ValidateDoc::validateApplicationDocument($input_data);
        foreach ($request->file as $file) {
            $path = $file->store('purchases', 'public');
            $created_file = Document::query()->create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime' => $file->getMimeType(),
                'size' => $file->getSize()]);
            array_push($result, $created_file);
        }
        return $result;
    }

    public static function create($request)
    {
        $user_id = Auth::user()->id;
        $user = User::query()->findOrFail($user_id);

        $application = Application::query()->create([
            'user_id' => $user_id,
            'role_id' => $user['role_id'],
            'document_id' => $request['document_id'],
            'object_id' => $request['object_id'],
            'status' => 'активный'
        ]);

        foreach ($request->applicationListArr as $value) {
            ApplicationList::query()->create([
                'application_id' => $application->id,
                'material' => $value['material'],
                'amount' => $value['amount']
            ]);
        }

        return self::showApplication([$application]);
    }


    public static function showApplicationUser()
    {
        $user_id = Auth::user()->id;
        $response = [];
        $apps = Application::query()->
        select(['applications.id','objects.address', 'applications.created_at as data_create', 'applications.document_id', 'applications.role_id', 'applications.status', 'applications.user_id'])
            ->join('objects','objects.id','applications.object_id')
            ->where('user_id', '=', $user_id)->get();
        foreach ($apps as $app){
            $user = User::query()->findOrFail($app->user_id);
            $app_lists = ApplicationList::query()
                ->where('application_id','=',$app->id)->get();
            array_push($response,array_merge($app->toArray(),[
                "author"=>$user->only(['surname','first_name']),
                "applicationList"=>$app_lists
            ]));
        }
        return response()->json($response);

    }

    public static function showApplication($applications, $id = null): array
    {
        $answer = array();
        if ($id != null) {
            $applications = Application::query()->where('id', '=', $id)->get();
        }


        foreach ($applications as $application) {
            $user = User::query()->findOrFail($application->user_id);
            $applicationListArr = ApplicationList::query()
                ->where('application_id', '=', $application->id)
                ->get();
            if ($application->role_id == 4) {
                $role_id = 'Прораб';
            } elseif ($application->role_id == 3) {
                $role_id = 'исполнитель';
            } else {
                $role_id = 'администратор';
            }
            array_push($answer, [
                'id' => $application->id,
                'role_id' => $role_id,
                'document_id' => $application->document_id,
                'author' => $user->only(['surname', 'first_name']),
                'user_id' => $application->user_id,
                'address' => ObjectB::query()->findOrFail($application->object_id)->address,
                'data_create' => $application->created_at,
                'status' => $application->status,
                'applicationList' => $applicationListArr
            ]);
        }

        return $answer;
    }


    static public function removeMatereal($id)
    {
        $applicationList = ApplicationList::query()->findOrFail($id);
        $application_id = $applicationList['id'];
        $applicationList->delete();
        return self::showApplication([Application::query()->findOrFail($application_id)]);
    }


    static public function updatePurchases(Request $request, $id)
    {
        $applicationList = ApplicationList::query()->findOrFail($id);
        $application_id = $applicationList['id'];

        $address = Application::query()->findOrFail($application_id);
        $address->object_id = $request->object_id;
        $address->save();
        $arrApplicationList_id = ApplicationList::query()->where('application_id', '=', $application_id)->get('id');
        $answer = [];
        $pol = [];
        $i = 1;
        foreach ($arrApplicationList_id as $a) {
            array_push($answer, ['a' => $a['id'], $i => $request->applicationListArr[$i]]);
            $applicationList = ApplicationList::query()->findOrFail($a['id']);
            $applicationList->material = $request->applicationListArr[$i]['material'];
            $applicationList->amount = $request->applicationListArr[$i]['amount'];
//            array_push($pol, $applicationList);
            $applicationList->save();
            $i++;
        }
//        return $pol;
        return self::showApplication([Application::query()->findOrFail($application_id)]);
    }

    static public function addMatereal($request, $id)
    {
        ValidateApplication::material($request);

        $application = Application::query()->findOrFail($id);
        ApplicationList::query()->create([
            'application_id' => $application['id'],
            'material' => $request->material,
            'amount' => $request->amount,
        ]);
        return self::showApplication([$application]);
    }


    static public function setStatusApplication($request, $id)
    {
        ValidateApplication::status($request);

        $application = Application::query()->findOrFail($id);
        $application->update($request->only(['status']));
        return self::showApplication([$application]);
    }

    static public function getListStatus(Request $request)
    {
        $listStatus = Application::query()->where('status', '=', $request->status)->get();
        return response()->json($listStatus);
    }

    static public function update($id, $request)
    {
        if (!$request->hasFile('file')) {
            return ['errors' => 'Файл не найден'];
        }
        $upDocument = Application::query()->findOrFail($id);
        $upDocument->status = 'архив';
        $upDocument->update();
        $response = [];
        ValidateDoc::validateApplicationDocument($request->all());
        foreach ($request->file as $file) {
            $path = $file->store('purchases', 'public');
            $created_file = Document::query()->create([
                'name' => $file->getClientOriginalName(),
                'object_id' => $upDocument->object_id,
                'path' => $path,
                'mime' => $file->getMimeType(),
                'size' => $file->getSize()]);
            $new_application = Application::query()->create([
                'status' => 'архив',
                'role_id' => $upDocument->role_id,
                'user_id' => $upDocument->user_id,
                'document_id' => $created_file->id,
                'object_id' => $upDocument->object_id
            ]);
            array_push($response, [
                "files" => $created_file,
                "application" => $new_application
            ]);
        }

        return ["data" => $response];
    }

    static public function delete($id)
    {
        ApplicationList::query()->where('application_id', '=', $id)->delete();
        Application::query()->findOrFail($id)->delete();
        return 'ok';
    }

}


