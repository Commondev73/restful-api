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

        $announces = Announces::where(function ($query) use ($keyword,$atype,$ptype,$bedroom,$area,$price) {
            $query->where('topic', 'like', '%' . trim($keyword) . '%')
                ->orWhere('announcer_status', 'like', '%' . trim($keyword) . '%')
                ->orWhere('province_code', 'like', '%' . trim($keyword) . '%')
                ->orWhere('amphoe_code', 'like', '%' . trim($keyword) . '%')
                ->orWhere('district_code', 'like', '%' . trim($keyword) . '%')
                ->orWhere('detail', 'like', '%' . trim($keyword) . '%');
                if(!is_null($atype))$query->orWhere('announcement_type', 'like', '%' . trim($atype) . '%');
                if(!is_null($ptype))$query->orWhere('Property_type', 'like', '%' . trim($ptype) . '%');
                if(!is_null($bedroom))$query->orWhere('bedroom', 'like', '%' . trim($bedroom) . '%') ;
                if(!is_null($area))$query->orWhere('area', 'like', '%' . trim($area) . '%')  ;
                if(!is_null($price))$query->orWhere('price', 'like', '%' . trim($price) . '%');
                
        })->where(function ($query) {
            $query->where('status', 1);
        })->get();

        foreach ($announces as $data) {
            $data->image = Image_announces::where('announcement_id', $data->id)->get();
        }

        return response()->json($announces, 200);
    }

}
