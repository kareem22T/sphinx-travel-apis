<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour_rating extends Model
{
    use HasFactory;

    protected $fillable = [
        "rate",
        "describe",
        "tour_id",
    ];

    protected $table = "tour_rating";
}
