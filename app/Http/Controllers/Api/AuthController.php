<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user()
    {
        $user = auth()->user();
        $data = [
            "id" =>  auth()->user()->id,
            "first_name" =>  auth()->user()->first_name,
            "last_name" =>  auth()->user()->last_name,
            "phone" =>  auth()->user()->phone,
            "line" =>  auth()->user()->line,
            "email" =>  auth()->user()->email,
            "image" => !is_null($user->image) ? url("/image/{$user->image}") : $user->image,
        ];

        // if (!is_null($user->image)) {
        //     $user->image = url("/image/{$user->image}");
        // }
        return response()->json($data);
    }

    public function refresh(Request $request)
    {
        $rule = ['token' => 'required'];
        $validator = Validator::make($request->only('token'), $rule);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $request->headers->set('Authorization', 'Bearer '. $request->token);
        try{
            return $this->respondWithToken(auth()->refresh());
        }catch (Exception $e){
            return response()->json($e->getMessage(),401);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
