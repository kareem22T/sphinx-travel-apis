<?php

namespace App\Models\Hotel\Rooms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    protected $fillable = [
        "price",
        "room_id",
        "currency_id",
    ];

    protected $table = "room_prices";
    public $timestamps = false;
    
    //Relations 
    public function room()
    {
        return $this->belongsTo('App\Models\Hotel\Rooms\Room', 'room_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id');
    }
}
