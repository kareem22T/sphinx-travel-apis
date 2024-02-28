<?php

namespace App\Models\Hotel\Rooms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory; 
    protected $fillable = [
        "path",
        "room_id",
    ];

    protected $table = "room_gallery";
    public $timestamps = false;
    
    //Relations 
    public function room()
    {
        return $this->belongsTo('App\Models\Hotel\Rooms\Room', 'room_id');
    }
}
