<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image_announces extends Model
{
    protected $table = "image_announces";
    
    protected $fillable = [
        'image_name',
        'announcement_id'
    ];

    public function announces()
    {
        return $this->belongsTo('App\Models\Announces');
    }
}
