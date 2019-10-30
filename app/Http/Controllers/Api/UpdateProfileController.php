<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;

class UpdateProfileController extends Controller
{
    public function Update(Request $request,$id)
    {
        $user = Users::find($id);
        $rules = [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'phone' => 'required|min:10|max:10',
            'line' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if($id == auth()->user()->id)
        {
            $input = $request->only([
                'first_name',
                'last_name',
                'phone',
                'line',
            ]);
            $user->update($input);
            return response()->json($user, 200);
        }
        return response()->json(["message" => "Record not found"], 404);
    }
}
