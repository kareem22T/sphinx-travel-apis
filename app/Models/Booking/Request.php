<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        "booking_details",
        "user_id",
        "status",
        "seen"
    ];

    protected $table = "requests";

    //Relations 
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }


}
