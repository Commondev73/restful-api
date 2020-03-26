<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announces;
use App\Models\Image_announces;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;
use File;

class AnnouncesController extends Controller
{
    public function index()
    {
        $announces = Announces::where('id_user', auth()->user()->id)->paginate(10);
        foreach ($announces as $data) {
            $getImage = Image_announces::where('announcement_id', $data->id)->get();
            foreach ($getImage as $dataImage) {
                $dataImage->image_name = url("/image/{$dataImage->image_name}");
            }
            $data->price = number_format($data->price);
            $data->image = $getImage;
        }
        return response()->json($announces, 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rules = [
            "announcer_status" => "required",
            "announcement_type" => "required",
            "Property_type" => "required",
            "province_name" => "required",
            "amphoe_name" => "required",
            "district_name" => "required",
            "province_code" => "required",
            "amphoe_code" => "required",
            "district_code" => "required",
            "topic" => "required|min:3",
            "detail" => "required|min:3",
            "bedroom" => "required",
            "toilet" => "required",
            "floor" => "required",
            "area" => "required",
            "price" => "required",
            "image" => "required",
            "image.*" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "status" => "required"
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $announces = new Announces;
        $announces->announcer_status = $request->announcer_status;
        $announces->announcement_type = $request->announcement_type;
        $announces->Property_type = $request->Property_type;
        $announces->province_name = $request->province_name;
        $announces->amphoe_name = $request->amphoe_name;
        $announces->district_name = $request->district_name;
        $announces->province_code = $request->province_code;
        $announces->amphoe_code = $request->amphoe_code;
        $announces->district_code = $request->district_code;
        $announces->topic = $request->topic;
        $announces->detail = $request->detail;
        $announces->bedroom = $request->bedroom;
        $announces->toilet = $request->toilet;
        $announces->floor = $request->floor;
        $announces->area = $request->area;
        $announces->price = $request->price;
        $announces->id_user = auth()->user()->id;
        $announces->status = $request->status;
        $announces->save();


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            foreach ($image as $file) {
                $new_name =  $announces->id . '-' . rand() . '-' . date("Y-m-d") . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'image/';
                $file->move($destinationPath, $new_name);
                $announces_img = new Image_announces;
                $announces_img->image_name = $new_name;
                $announces_img->announcement_id = $announces->id;
                $announces_img->save();
            }
        }

        $announces_id = Announces::find($announces->id);
        $getImage = Image_announces::where('announcement_id', $announces->id)->get();
        foreach ($getImage as $dataImage) {
            $dataImage->image_name = url("/image/{$dataImage->image_name}");
        }
        $announces_id->price = number_format($announces_id->price);
        $announces_id->image = $getImage;
        return response()->json($announces_id, 201);
    }

    public function show($id)
    {
        $announces = Announces::find($id);
        if ($announces->id_user == auth()->user()->id) {
            $getImage = Image_announces::where('announcement_id', $announces->id)->get();
            foreach ($getImage as $dataImage) {
                $dataImage->image_name = url("/image/{$dataImage->image_name}");
            }
            $announces->price = number_format($announces->price);
            $announces->image = $getImage;
            return response()->json($announces, 200);
        }
        return response()->json(["message" => "Record not found"], 404);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "announcer_status" => "required",
            "announcement_type" => "required",
            "Property_type" => "required",
            "province_name" => "required",
            "amphoe_name" => "required",
            "district_name" => "required",
            "province_code" => "required",
            "amphoe_code" => "required",
            "district_code" => "required",
            "topic" => "required|min:3",
            "detail" => "required|min:3",
            "bedroom" => "required",
            "toilet" => "required",
            "floor" => "required",
            "area" => "required",
            "price" => "required",
            "status" => "required"
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $announces = Announces::find($id);
        if ($announces->id_user == auth()->user()->id) {

            $announces->update($request->all());

            if ($request->hasFile('image')) {

                $request->validate([
                    "image" => "required",
                    "image.*" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048"
                ]);

                $image = $request->file('image');
                foreach ($image as $file) {
                    $new_name =  $announces->id . '-' . rand() . '-' . date("Y-m-d") . '.' . $file->getClientOriginalExtension();
                    $destinationPath = 'image/';
                    $file->move($destinationPath, $new_name);
                    $announces_img = new Image_announces;
                    $announces_img->image_name = $new_name;
                    $announces_img->announcement_id = $announces->id;
                    $announces_img->save();
                }
            }

            if ($request->has('delete_image')) {
                $delete_image = Image_announces::whereIn('id', $request->delete_image);
                foreach ($delete_image->get() as $Image) {
                    if (File::exists(public_path('image/' . $Image->image_name))) {
                        File::delete(public_path('image/' . $Image->image_name));
                    }
                }
                $delete_image->delete();
            }
            
            $getImage = Image_announces::where('announcement_id', $id)->get();
            foreach ($getImage as $dataImage) {
                $dataImage->image_name = url("/image/{$dataImage->image_name}");
            }
            $announces->price = number_format($announces->price);
            $announces->image = $getImage;
            return response()->json($announces, 200);
        }
        return response()->json(["message" => "Record not found"], 404);
    }

    public function destroy($id)
    {
        $announces = Announces::find($id);
        if ($announces->id_user == auth()->user()->id) {
            $images = Image_announces::where('announcement_id', $id)->get();
            foreach ($images as $image) {
                if (File::exists(public_path('image/' . $image->image_name))) {
                    File::delete(public_path('image/' . $image->image_name));
                }
            }
            $announces->delete();
            return response()->json(null, 204);
        }
        return response()->json(["message" => "Record not found"], 404);
    }
}
