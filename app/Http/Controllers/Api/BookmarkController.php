<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\PublicAnnouncesController;
use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Announces;
use App\Models\Image_announces;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmark = Bookmark::where('id_user', auth()->user()->id)->get();
        foreach ($bookmark as $dataID) {
            $id[] = $dataID->id_announces;
        }
        $announces = Announces::whereIn('id', $id)->paginate(28);
        foreach ($announces as $data) {
            $getImage = Image_announces::where('announcement_id', $data->id)->get();
            foreach ($getImage as $dataImage) {
                $dataImage->image_name = url("/image/{$dataImage->image_name}");
            }
            $data->price = number_format($data->price);
            $data->image =  $getImage;
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
            'id_announces' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $bookmark = new Bookmark;
        $bookmark->id_user = auth()->user()->id;
        $bookmark->id_announces = $request->id_announces;
        $bookmark->save();

        return response()->json($bookmark, 201);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $bookmark = Bookmark::where('id_user', auth()->user()->id)
            ->where('id_announces', $id)->delete();
        return response()->json(null, 204);
    }

    public function getId()
    {
        $bookmark = Bookmark::where('id_user', auth()->user()->id)->get();
        foreach ($bookmark as $dataID) {
            $id[] = $dataID->id_announces;
        }
        return response()->json($id, 200);
    }
}
