<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetRequest;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetController extends Controller
{
    public function resetpassword(ResetRequest $request)
    {
        $email = $request->email;
        $token = $request->token;
        $password = Hash::make($request->password);
        $emailCheck = DB::table('password_resets')->where('email', $email)->first();
        $pinCheck = DB::table('password_resets')->where('token', $token)->first();

        if (!$emailCheck) {
            return response([
                'message' => 'notfound'
            ], 401);
        }
        if (!$pinCheck) {
            return response([
                'message' => 'pin code invalid'
            ], 401);
        }
        DB::table('users')->where('email', $email)->update(['password' => $password]);
        DB::table('password_resets')->where('email', $email)->delete();
        return response([
            'message' => 'password Change successfully'
        ], 200);
    }
}
