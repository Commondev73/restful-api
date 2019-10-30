<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    public function Register(Request $request)
    {
        $rules = [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'phone' => 'required|min:10|max:10',
            'line' => 'required',
            'email' => 'required|min:3',
            'password' => 'required|min:8',
            'password_confirm' => 'required|min:8|same:password',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $users = new Users;
        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->phone = $request->phone;
        $users->line = $request->line;
        $users->email = $request->email;
        $users->password = Hash::make($request->password);
        $users->save();
        return response()->json($users, 201);
    }  
}
