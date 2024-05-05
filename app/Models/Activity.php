<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        "name_ar",
        "name_en",
        "desc_ar",
        "desc_en",
        "thumbnail_path",
    ];


    protected $table = "activities";
}
