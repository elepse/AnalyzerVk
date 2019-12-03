<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use ATehnix\VkClient as VK;
use ATehnix\VkClient\Client;


class VkController extends Controller
{
    public $auth = null;
    public $token = null;
    public $vkClient = null;

    function __construct()
    {
        $this->auth = new VK\Auth('7227040', 'Yfw9yfyB9CTBNqj6tyxt', 'http://127.0.0.1:8000/api/vk/accessToken');
        $this->token = Cookie::get('vkToken');
        $this->vkClient = new Client('5.103');
        $this->vkClient->setDefaultToken($this->token);
    }

    public function authVk()
    {
        return redirect('https://oauth.vk.com/authorize?client_id=7227040&scope=&redirect_uri=http://127.0.0.1:8000/api/vk/accessToken&response_type=code&display=page');
    }

    public function getCode()
    {
        $token = $this->auth->getToken($_GET['code']);
        $cookie = cookie('vkToken', $token, 1400);
        return redirect('/')->cookie($cookie);
    }

    public function collectData($idGroup)
    {
        $studentsData = [];
        $studentsIds = [];
        $students = Student::query()->where('group_id', '=', "$idGroup")->get('vk_link');

        //получаем айди пользователей
        foreach ($students as $student) {
            $vkId = explode("/", $student->vk_link);
            $vkId = $vkId[count($vkId) - 1];
            array_push($studentsIds, $vkId);
        }
        $studentsIds = implode(",", $studentsIds);

        //отправляем запрос к вк апи
        $request = new VK\Requests\Request('users.get', ['user_ids' => $studentsIds]);
        $studentsIds = $this->vkClient->send($request);

        foreach ($studentsIds['response'] as $student) {
            array_push($studentsData, new VK\Requests\Request('wall.get', ['owner_id' => $student['id']]));
        }
        if (!is_null($idGroup)) {
            $execute = VK\Requests\ExecuteRequest::make($studentsData);
            $response = $this->vkClient->send($execute);

            return response()->json([
                'vk' => $response,
                'status' => 'success'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error'
            ], 400);
        }
    }

    public function saveLink(Request $request) {
        $vkLink = $request->get('vkLink', null);
        $studentId = $request->get('studentId', null);

        $vkId = explode('/',$vkLink);
        $vkId = $vkId[count($vkId) - 1];
        $vkRequest = new VK\Requests\Request('users.get', ['user_ids' => $vkId]);
        $userData = $this->vkClient->send($vkRequest);

        if (!$userData['response'][0]['is_closed']){
            Student::find($studentId)->update(['vk_id' => $userData['response'][0]['id'], 'vk_link' => $vkLink]);
            return response()->json([
               'status' => 'success'
            ], 200);
        }else {
            return response()->json([
                'status' => 'error',
                'errors' => 'Профиль пользователя приватный.'
            ], 400);
        }

    }
}
