<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use ATehnix\VkClient as VK;
use ATehnix\VkClient\Client;


class VkController extends Controller
{
    public $auth = null;
    public $token = null;
    function __construct()
    {
        $this->auth = new VK\Auth('7227040', 'Yfw9yfyB9CTBNqj6tyxt', 'http://127.0.0.1:8000/api/vk/accessToken');
        $this->token = Cookie::get('vkToken');
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

    public function collectData(Request $request)
    {
        $api = new Client('5.103');
        $api->setDefaultToken("94389a98c0092b702bdcf933afe6440ac73a33937ea4e2f64bb1ab36ab759b397d5d996e471855d08079b");
        $userId = $request->get('userId');
        $query = new VK\Requests\Request('users.get', ['user_ids' => $userId]);
        $response = $api->send($query);
        var_dump($response);
    }

}
