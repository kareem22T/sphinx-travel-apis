<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallary extends Model
{
    use HasFactory;
    protected $fillable = [
        "path",
        "car_id",
    ];

    protected $table = "car_gallery";
    public $timestamps = false;
    
    //Relations 
    public function car()
    {
        return $this->belongsTo('App\Models\Car\Car', 'car_id');
    }

}
