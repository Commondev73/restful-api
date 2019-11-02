<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistrictsController extends Controller
{
    public function province()
    {
        $province = DB::table('districts')->get();
        return response()->json($province, 200);
    }

    public function amphoe($code = null)
    {
        $amphoe = DB::table('districts')->where('province_code',$code)->get();
        return response()->json($amphoe, 200);
    }   

    public function district($code = null)
    {
        $district = DB::table('districts')->where('amphoe_code',$code)->get();
        return response()->json($district, 200);
    }

    public function districts($code = null)
    {
        $district = DB::table('districts')->where('district_code',$code)->first();
        return response()->json($district, 200);
    }
}
