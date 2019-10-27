<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announces;
use App\Models\Image_announces;

class PublicAnnouncesController extends Controller
{
    public function announces()
    {
        $announces = Announces::where('status', 1)->get();
        foreach ($announces as $data) {
            $data->image = Image_announces::where('announcement_id', $data->id)->get();
        }
        return response()->json($announces, 200);
    }

    public function announcesByID($id)
    {
        $announces = Announces::find($id);
        if ($announces->status == 1) {
            $announces->image = Image_announces::where('announcement_id', $announces->id)->get();
            return response()->json($announces, 200);
        }
        return response()->json(["message" => "Record not found"], 404);
    }
}
