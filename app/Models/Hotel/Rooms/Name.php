<?php

namespace App\Models\Hotel\Rooms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Name extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "room_id",
        "language_id",
    ];

    protected $table = "room_names";
    public $timestamps = false;
    
    //Relations 
    public function room()
    {
        return $this->belongsTo('App\Models\Hotel\Rooms\Room', 'room_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }

}
