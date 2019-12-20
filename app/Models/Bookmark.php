<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = "bookmark";

    protected $fillable = [
        'id_user',
        'id_announces'
    ];
}
