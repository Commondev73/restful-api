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
    protected $id_user;

    public function __construct()
    {
        $this->id_user = auth()->user()->id;
    }

    // public function index(PublicAnnouncesController $Announces)
    // {
    //     $bookmark = Bookmark::where('id_user', $this->id_user)->get();
    //     foreach($bookmark as $data){
    //         $list[] = $Announces->announcesByID($data->id_announces);
    //     }
    //     return response()->json($list, 200);
    // }

    public function index()
    {
        $bookmark = Bookmark::where('id_user', $this->id_user)->get();
        foreach ($bookmark as $dataID) {
            $id[] = $dataID->id_announces;
        }
        $announces = Announces::find($id);
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
            'id_announces' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $bookmark = new Bookmark;
        $bookmark->id_user = $this->id_user;
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
        $bookmark = Bookmark::where('id_user', $this->id_user)
            ->where('id_announces', $id)->delete();
        return response()->json(null, 204);
    }
}
