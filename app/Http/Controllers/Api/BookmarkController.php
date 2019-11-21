<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Announces;
use App\Models\Image_announces;
use App\Http\Controllers\Api\PublicAnnouncesController;
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

    public function index()
    {
        $bookmark = Bookmark::where('id_user', $this->id_user)->get();
        $PublicAnnounces = new PublicAnnounces();
        foreach ($bookmark as $data) {
            $announces_id = $PublicAnnounces->announcesByID($data->id_announces);
            return response()->json($announces_id, 200);
         }
        // return response()->json($bookmark, 200);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $rules= [
            'id_user'=> 'required',
            'id_announces'=>'required'
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $bookmark = new Bookmark;
        $bookmark->id_user = $request->id_user;
        $bookmark->id_announces = $request->id_announces;
        $bookmark->save();

        return respones()->json($bookmark,201);
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
        $bookmark = Bookmark::find($id);
        $bookmark->delete();
        return response()->json(null, 204);
    }
}
