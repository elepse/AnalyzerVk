<?php

namespace App\Http\Controllers;

use App\attach;
use App\Student;
use App\VkPost;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use ATehnix\VkClient as VK;
use ATehnix\VkClient\Client;
use Illuminate\Support\Facades\DB;


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
        $students = Student::query()->where('group_id', '=', "$idGroup")->get('vk_id');

        foreach ($students as $student) {
            array_push($studentsData, new VK\Requests\Request('wall.get', ['owner_id' => $student['vk_id']]));
        }
        if (!is_null($idGroup)) {
            $execute = VK\Requests\ExecuteRequest::make($studentsData);
            $response = $this->vkClient->send($execute);

            foreach ($response['response'] as $userWall) {
                foreach ($userWall['items'] as $post) {
                    try {
                        DB::transaction(function () use ($post) {
                            if (key_exists('copy_history', $post)) {
                                $owner = $post['owner_id'];
                                $post = $post['copy_history'][0];
                                $is_repost = true;
                            } else {
                                $is_repost = false;
                                $owner = $post['owner_id'];
                            }
                            $vkPost = (new VkPost())->fill([
                                'id_vk_post' => $post['id'],
                                'id_vk_student' => $owner,
                                'text' => $post['text'],
                                'date' => $post['date'],
                                'is_repost' => $is_repost,
                            ]);
                            $vkPost->save();
                            $newPost = $vkPost->id_vk_post;

                            if (key_exists('attachments', $post)) {
                                foreach ($post['attachments'] as $attach) {
                                    $type = $attach['type'];
                                    if ($type === 'photo') {
                                        $url = $attach[$type]['sizes'][count($attach[$type]['sizes']) - 1]['url'];
                                        (new Attach())->fill([
                                            'id_post' => $newPost,
                                            'type' => $type,
                                            'title' => $attach[$type]['text'],
                                            'url' => $url,
                                        ])->save();
                                    } else {
                                        (new Attach())->fill([
                                            'id_post' => $newPost,
                                            'type' => $type,
                                            'title' => $attach[$type]['title'],
                                            'url' => $attach[$type]['url'],
                                        ])->save();
                                    }
                                }
                            }
                        });
                    } catch (\PDOException $e) {

                    } catch (\Exception $e) {

                    }
                }
            }
            return response()->json([
                'status' => 'success'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error'
            ], 400);
        }
    }

    public function saveLink(Request $request)
    {
        $vkLink = $request->get('vkLink', null);
        $studentId = $request->get('studentId', null);

        if ($vkLink === null || $vkLink === '') {
            Student::find($studentId)->update(['vk_id' => null, 'vk_link' => $vkLink]);
            return response()->json([
                'status' => 'success'
            ], 200);
        } else {
            $vkId = explode('/', $vkLink);
            $vkId = $vkId[count($vkId) - 1];
            $vkRequest = new VK\Requests\Request('users.get', ['user_ids' => $vkId]);
            $userData = $this->vkClient->send($vkRequest);

            if (!$userData['response'][0]['is_closed']) {
                Student::find($studentId)->update(['vk_id' => $userData['response'][0]['id'], 'vk_link' => $vkLink]);
                return response()->json([
                    'status' => 'success'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'errors' => 'Профиль пользователя приватный.'
                ], 400);
            }
        }
    }
}
