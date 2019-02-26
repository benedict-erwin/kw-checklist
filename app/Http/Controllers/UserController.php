<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['only'=>['deauth']]);
    }

    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->input('username'))
            ->where('password', hash('sha256', $request->input('password')))
            ->first();

        if (!empty($user)) {
            $apikey = hash('sha512', base64_encode(str_random(64)));
            User::where('username', $request->input('username'))->update(['api_key' => "$apikey"]);
            return response()->json(['status' => 'success','api_key' => $apikey]);
        } else {
            return response()->json(['status' => 'fail'], 401);
        }
    }

    public function deauth(Request $request)
    {
        $key = explode(' ', $request->header('Authorization'));
        $user = User::where('api_key', $key[1])->first();
        User::where('api_key', $key[1])->update(['api_key' => ""]);
        return response()->json(['status' => 'success','api_key' => ""]);
    }
}
