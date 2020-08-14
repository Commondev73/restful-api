<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announces;
use App\Models\Image_announces;
use App\Models\Users;
use Illuminate\Support\Facades\DB;

class PublicAnnouncesController extends Controller
{
  public function announces()
  {
    $announces = Announces::where('status', 1)->orderBy('created_at', 'desc')->paginate(28);
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
        "image" => !is_null($getUser->image) ? url("/image/{$getUser->image}") : $getUser->image,
      ];
      return response()->json($announces, 200);
    }
    return response()->json(["message" => "Record not found"], 404);
  }

  public function search(Request $request)
  {
    $announces = Announces::where('status', 1)->orderBy('created_at', 'desc');

    if ($request->has('atype')) $announces->where('announcement_type', $request->atype);

    if ($request->has('ptype')) $announces->where('Property_type', $request->ptype);

    if ($request->has('bedroom')) $announces->where('bedroom', ($request->bedroom == 5 ? '>=' : '='), $request->bedroom);

    if ($request->has('toilet')) $announces->where('toilet', ($request->toilet == 5 ? '>=' : '='), $request->toilet);

    if ($request->has('price') && $request->has('toprice')) $announces->whereBetween('price', [trim($request->price), trim($request->toprice)]);

    if ($request->has('keyword')) {
      $announces->where(function ($query) use ($request) {
        $query->where('topic', 'like', '%' . trim($request->keyword) . '%')
          ->orWhere('province_name', 'like', '%' . trim($request->keyword) . '%')
          ->orWhere('amphoe_name', 'like', '%' . trim($request->keyword) . '%')
          ->orWhere('district_name', 'like', '%' . trim($request->keyword) . '%');
      });
    }

    $result = $announces->paginate(28);

    foreach ($result as $data) {
      $getImage = Image_announces::where('announcement_id', $data->id)->get();
      foreach ($getImage as $dataImage) {
        $dataImage->image_name = url("/image/{$dataImage->image_name}");
      }
      $data->price = number_format($data->price);
      $data->image = $getImage;
    }

    return response()->json($result, 200);
  }
}
