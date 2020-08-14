<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announces;
use App\Models\Image_announces;
use App\Http\Controllers\Api\MailReadStatusController;
use Illuminate\Support\Facades\Auth;

class StatusAnnouncesController extends Controller
{   
    public function announcesCount()
    {   
        $callFunction = new MailReadStatusController;
        $announces = Announces::where('id_user', auth()->user()->id)->get()->toArray();
        $result = [
            'draft' => $callFunction->find_children($announces, 'status', 0),
            'online' => $callFunction->find_children($announces, 'status', 1),
            'correct' => $callFunction->find_children($announces, 'status', 2)
          ];
          return response()->json($result, 200);
    }

    protected function status($value)
    {
        $announces = Announces::where('id_user', auth()->user()->id)->where('status', $value)->orderBy('created_at', 'desc')->paginate(20);
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

    public function online()
    {
        return $this->status(1);
    }

    public function draft()
    {
        return $this->status(0);
    }

    public function correct()
    {
        return $this->status(2);
    }
}
