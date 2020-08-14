<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $table = "mail";

    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'id_user',
        'reading_status'
    ];
}
