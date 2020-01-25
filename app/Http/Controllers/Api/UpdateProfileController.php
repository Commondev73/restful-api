<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;

class UpdateProfileController extends Controller
{
    public function Update(Request $request)
    {
        $user = Users::find(auth()->user()->id);
        $rules = [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'phone' => 'required|min:10|max:10',
            'line' => 'required',
            'email' => 'required|string|email|max:191|unique:users,email,' . auth()->user()->id . ''
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $input = $request->only([
            'first_name',
            'last_name',
            'phone',
            'line',
            'email'
        ]);
        $user->update($input);

        $data = [
            "id" =>  $user->id,
            "first_name" =>  $user->first_name,
            "last_name" =>  $user->last_name,
            "phone" =>  $user->phone,
            "line" =>  $user->line,
            "email" =>  $user->email,
            "image" => !is_null($user->image) ? url("/image/{$user->image}") : $user->image,
        ];
        return response()->json($data, 200);
    }
}
