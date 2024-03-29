<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    protected $fillable = [
        "price",
        "car_id",
        "currency_id",
    ];

    protected $table = "car_prices";
    public $timestamps = false;
    
    //Relations 
    public function car()
    {
        return $this->belongsTo('App\Models\Car\Car', 'car_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id');
    }
}
