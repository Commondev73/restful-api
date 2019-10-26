<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;
use File;

class ImageProfileController extends Controller
{
    protected $id;

    public function __construct()
    {
        $this->id_user = auth()->user()->id;
    }

    public function ImageProfile(Request $request)
    {
        $rules = [
            "imageprofile" => "required",
            "imageprofile.*"=>"image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ];

        $validator = Validator::make($request->only('imageprofile'), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $imageprofile = $request->file('imageprofile');
        $new_name = $this->id_user . '-' . rand() . '.' . $imageprofile->getClientOriginalExtension();
        $destinationPath = 'image/';
        $imageprofile->move($destinationPath, $new_name);

        $user = Users::find($this->id_user);
        if (File::exists(public_path('image/' . $user->image))) {
            File::delete(public_path('image/' . $user->image));
        }

        $user->image = $new_name;
        $user->save();

        return response()->json($user, 200);
    }

    public function DeleteImageProfile()
    {
        if (File::exists(public_path('image/' . auth()->user()->image))) {
            File::delete(public_path('image/' . auth()->user()->image));
        }

        $user = Users::find($this->id_user);
        $user->image = null;
        $user->update();
        
        return response()->json(null, 204);
    }
}
