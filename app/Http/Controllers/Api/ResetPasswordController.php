<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function ResetPassword(Request $request)
    {
        $rules = [
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'new_password_confirm' => 'required|min:8|same:new_password'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if(Hash::check($request->old_password,auth()->user()->password))
        {
            $user = Users::find(auth()->user()->id);
            $user->password = Hash::make($request->new_password);
            $user->update();
            return response()->json($user, 200);
        }
        return response()->json(["message"=>"Password is incorrect"], 401);
    }
}
