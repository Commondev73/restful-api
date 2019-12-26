<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announces;
use App\Models\Image_announces;
use App\Models\Users;

class PublicAnnouncesController extends Controller
{
    public function announces()
    {
        $announces = Announces::where('status', 1)->get();
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

    public function announcesByID($id)
    {
        $announces = Announces::find($id);
        if (is_null($announces)) {
            return response()->json(["message" => "Record not found"], 404);
        }
        if ($announces->status == 1) {
            $getImage = Image_announces::where('announcement_id', $announces->id)->get();
            $getUser = Users::find($announces->id_user);
            foreach ($getImage as $dataImage) {
                $dataImage->image_name = url("/image/{$dataImage->image_name}");
            }
            $announces->price = number_format($announces->price);
            $announces->image = $getImage;
            $announces->user = [
                "first_name" => $getUser->first_name,
                "phone" => $getUser->phone,
                "line" => $getUser->line,
                "email" => $getUser->email,
            ];
            return response()->json($announces, 200);
        }
        return response()->json(["message" => "Record not found"], 404);
    }

    public function search($keyword = null, $atype = null, $ptype = null, $bedroom = null, $area = null, $price = null)
    {
        // SELECT * FROM `announces` WHERE (`topic`LIKE'%bbb%' 
        // OR `announcement_type`LIKE'%bbb%' 
        // OR `Property_type` LIKE '%bbb%' 
        // OR `province_code` LIKE'%bbb%'
        // OR `amphoe_code` LIKE '%bbb%'
        // OR `district_code`LIKE '%bbb%'
        // OR `announcer_status`LIKE'%bbb%'
        // OR `detail`LIKE '%bbb%'
        // OR `price`LIKE'%bbb%') AND (`status` = 1)

        $announces = Announces::where(function ($query) use ($keyword, $atype, $ptype, $bedroom, $area, $price) {
            $query->where('topic', 'like', '%' . trim($keyword) . '%')
                ->orWhere('announcer_status', 'like', '%' . trim($keyword) . '%')
                ->orWhere('province_name', 'like', '%' . trim($keyword) . '%')
                ->orWhere('amphoe_name', 'like', '%' . trim($keyword) . '%')
                ->orWhere('district_name', 'like', '%' . trim($keyword) . '%')
                ->orWhere('detail', 'like', '%' . trim($keyword) . '%');
            if (!is_null($atype)) $query->orWhere('announcement_type', 'like', '%' . trim($atype) . '%');
            if (!is_null($ptype)) $query->orWhere('Property_type', 'like', '%' . trim($ptype) . '%');
            if (!is_null($bedroom)) $query->orWhere('bedroom', 'like', '%' . trim($bedroom) . '%');
            if (!is_null($area)) $query->orWhere('area', 'like', '%' . trim($area) . '%');
            if (!is_null($price)) $query->orWhere('price', 'like', '%' . trim($price) . '%');
        })->where(function ($query) {
            $query->where('status', 1);
        })->get();

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
}
