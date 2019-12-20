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

class Admin_AnnouncesController extends Controller
{
    protected $id_user;

    public function __construct()
    {
        $this->id_user = auth()->user()->id;
    }

    public function index()
    {
        $announces = Announces::all();
        foreach ($announces as $data) {
            $data->image =  Image_announces::where('announcement_id', $data->id)->get();
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
            "image.*"=>"image|mimes:jpeg,png,jpg,gif,svg|max:2048"
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
        $announces->id_user = $this->id_user;
        $announces->status = 1;
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
        $announces_id->image = Image_announces::where('announcement_id', $announces->id)->get();
        return response()->json($announces_id, 201);
    }

    public function show($id)
    {
        $announces = Announces::find($id);
        if (is_null($announces)) {
            return response()->json(["message" => "Record not found"], 404);
        }
        $announces->image = Image_announces::where('announcement_id', $id)->get();
        return response()->json($announces, 200);
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
            "price" => "required"
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $announces = Announces::find($id);
        if (is_null($announces)) {
            return response()->json(["message" => "Record not found"], 404);
        }
        $announces->update($request->all());

        if ($request->hasFile('image')) {

            $request->validate([
                "image" => "required",
                "image.*"=>"image|mimes:jpeg,png,jpg,gif,svg|max:2048"
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

        $announces->image = Image_announces::where('announcement_id', $id)->get();
        return response()->json($announces, 200);
    }

    public function destroy($id)
    {
        $announces = Announces::find($id);
        if (is_null($announces)) {
            return response()->json(["message" => "Record not found"], 404);
        }

        $images = Image_announces::where('announcement_id', $id)->get();
        foreach ($images as $image) {
            if (File::exists(public_path('image/' . $image->image_name))) {
                File::delete(public_path('image/' . $image->image_name));
            }
        }

        $announces->delete();
        return response()->json(null, 204);
    }
}
