<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announces extends Model
{
    protected $table = "announces";
    
    protected $fillable = [
        'announcer_status',
        'announcement_type',
        'Property_type',
        'province_code',
        'amphoe_code',
        'district_code',
        'topic',
        'detail',
        'bedroom',
        'toilet',
        'floor',
        'area',
        'price',
        'id_user'
    ];
    
    public function images()
    {
        return $this->hasMany('App\Models\Image_announces');
    }
}
