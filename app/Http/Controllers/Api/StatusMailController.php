<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StatusMailController extends Controller
{
  protected function status($value)
  {
    $Mail = Mail::where('id_user', auth()->user()->id)->where('reading_status', $value)->orderBy('created_at', 'desc')->paginate(30);
    return response()->json($Mail, 200);
  }

  public function read()
  {
    return $this->status(0);
  }

  public function unread()
  {
    return $this->status(1);
  }

  public function save()
  {
    return $this->status(2);
  }
}
