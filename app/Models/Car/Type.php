<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $fillable = [
        "type",
        "car_id",
        "language_id",
    ];

    protected $table = "car_types";
    public $timestamps = false;
    
    //Relations 
    public function car()
    {
        return $this->belongsTo('App\Models\Car\Car', 'car_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
}
