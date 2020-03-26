<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mail = Mail::select('id', 'email', 'message', 'reading_status', 'created_at')->where('id_user', auth()->user()->id)->paginate(30);
        return response()->json($mail, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|string|email|max:191',
            'message' => 'required',
            'id_user' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $mail = new Mail;
        $mail->name = $request->name;
        $mail->phone = $request->phone;
        $mail->email = $request->email;
        $mail->message = $request->message;
        $mail->id_user =  $request->id_user;
        $mail->save();
        return response()->json($mail, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mail = Mail::find($id);
        if (is_null($mail)) return response()->json(["message" => "Record not found"], 404);
        if ($mail->id_user == auth()->user()->id) {
            return response()->json($mail, 200);
        }
        return response()->json(["message" => "Record not found"], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mail = Mail::find($id);
        if ($mail->id_user == auth()->user()->id) {
            $mail->delete();
            return response()->json(null, 204);
        }
        return response()->json(["message" => "Record not found"], 404);
    }
}
