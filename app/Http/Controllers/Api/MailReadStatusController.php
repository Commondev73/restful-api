<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MailReadStatusController extends Controller
{
  public function find_children($array, $key, $parent)
  {
    return count(array_filter($array, function ($e) use ($parent, $key) {
      return $e[$key] === $parent;
    }));
  }

  public function read($id)
  {
    $mail = Mail::find($id);
    if ($mail->id_user == auth()->user()->id) {
      $mail->reading_status = 0;
      $mail->save();
      return response()->json(null, 204);
    }
    return response()->json(["message" => "Record not found"], 404);
  }

  public function unread($id)
  {
    $mail = Mail::find($id);
    if ($mail->id_user == auth()->user()->id) {
      $mail->reading_status = 1;
      $mail->save();
      return response()->json(null, 204);
    }
    return response()->json(["message" => "Record not found"], 404);
  }

  public function save($id)
  {
    $mail = Mail::find($id);
    if ($mail->id_user == auth()->user()->id) {
      $mail->reading_status = 2;
      $mail->save();
      return response()->json(null, 204);
    }
    return response()->json(["message" => "Record not found"], 404);
  }

  public function mailCount()
  {
    $mail = Mail::where('id_user', auth()->user()->id)->get()->toArray();
    $result = [
      'unread' => $this->find_children($mail, 'reading_status', 0),
      'read' => $this->find_children($mail, 'reading_status', 1),
      'save' => $this->find_children($mail, 'reading_status', 2)
    ];
    return response()->json($result, 200);
  }
}
