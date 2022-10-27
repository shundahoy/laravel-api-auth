<?php

namespace App\Http\Controllers;

// use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Bridge\AccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $email = $request->email;
            $password = $request->password;
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                /** @var \App\Models\MyUserModel $user **/
                $user = Auth::user();
                $token = $user->createToken('app')->accessToken;
                return response([
                    'message' => 'login sccess',
                    'token' => $token,
                    'user' => $user
                ], 200);
            }
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
        return response([
            'message' => 'failed'
        ], 401);
    }

    public function register(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = $user->createToken('app')->accessToken;
            return response([
                'message' => 'register sccess',
                'token' => $token,
                'user' => $user
            ], 200);
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
        return response([
            'message' => 'failed'
        ], 401);
    }
}
