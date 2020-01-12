<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class RegisterController extends Controller
{
    public function Register(Request $request)
    {
        $rules = [
            'first_name' => 'required|min:3|max:191',
            'last_name' => 'required|min:3|max:191',
            'phone' => 'required|min:10|max:10',
            'email' => 'required|string|email|max:191|unique:users,email',
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
        $users->line = "";
        $users->email = $request->email;
        $users->password = Hash::make($request->password);
        $users->save();
        return response()->json($users, 201);
    }

    public  function ValidationEmail(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:191|unique:users,email'
        ];
        $validator = Validator::make($request->only('email'), $rules);
        if ($validator->fails()) {
            return response()->json(false, 400);
        }
        return response()->json(true, 200);
    }
}
