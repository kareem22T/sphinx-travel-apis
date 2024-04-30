<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    protected $fillable = [
        "name_ar",
        "name_en",
        "desc_ar",
        "desc_en",
        "thumbnail_path",
    ];

    //realtions
    public function tours()
    {
        return $this->hasMany('App\Models\Tour\Tour', 'destination_id');
    }

    public function hotels()
    {
        return $this->hasMany('App\Models\Hotel\Hotel', 'destination_id');
    }


}
