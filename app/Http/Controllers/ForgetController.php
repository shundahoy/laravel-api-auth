<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetRequest;
use App\Mail\ForgetMail;
use App\Models\User;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgetController extends Controller
{
    public function forgetpassword(ForgetRequest $request)
    {
        $email = $request->email;
        if (User::where('email', $email)->doesntExist()) {
            return response([
                'message' => 'email invalid'
            ], 401);
        }
        $token = rand(10, 100000);
        try {

            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token
            ]);
            Mail::to($email)->send(new ForgetMail($token));
            return response([
                'message' => 'reset password mail'
            ], 200);
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
