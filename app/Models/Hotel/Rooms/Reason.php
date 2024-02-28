<?php

namespace App\Models\Hotel\Rooms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    use HasFactory;
    protected $fillable = [
        "hotel_id"
    ];

    public $timestamps = false;
}
