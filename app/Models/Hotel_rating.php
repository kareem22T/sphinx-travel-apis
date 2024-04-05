<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel_rating extends Model
{
    use HasFactory;
    protected $fillable = [
        "hotel_id",
        "staff",
        "facilities",
        "cleanliness",
        "comfort",
        "money",
        "location",
    ];

    protected $table = "hotel_rating";
}
