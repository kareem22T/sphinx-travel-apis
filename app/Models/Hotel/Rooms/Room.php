<?php

namespace App\Models\Hotel\Rooms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "hotel_id",
    ];

    //Relations 
    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel\Hotel', 'hotel_id');
    }

    public function names()
    {
        return $this->hasMany('App\Models\Hotel\Rooms\Name', 'room_id');
    }
    
    public function prices()
    {
        return $this->hasMany('App\Models\Hotel\Rooms\Price', 'room_id');
    }
    
    public function descriptions()
    {
        return $this->hasMany('App\Models\Hotel\Rooms\Description', 'room_id');
    }
    public function gallery()
    {
        return $this->hasMany('App\Models\Hotel\Rooms\Gallery', 'room_id');
    }
    public function features()
    {
        return $this->belongsToMany('App\Models\Feature', 'room_features', 'room_id', 'feature_id', 'id', 'id');
    }
}
