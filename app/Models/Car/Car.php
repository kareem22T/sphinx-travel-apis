<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "phone",
        "lat",
        "lng",
        "address",
        "address_name"
    ];
    public function titles()
    {
        return $this->hasMany('App\Models\Car\Title', 'car_id');
    }
    
    public function prices()
    {
        return $this->hasMany('App\Models\Hotel\Car\Price', 'car_id');
    }
    
    public function descriptions()
    {
        return $this->hasMany('App\Models\Hotel\Car\Description', 'car_id');
    }
    public function gallery()
    {
        return $this->hasMany('App\Models\Hotel\Car\Gallery', 'car_id');
    }
    public function types()
    {
        return $this->hasMany('App\Models\Hotel\Car\Type', 'car_id');
    }
    public function features()
    {
        return $this->belongsToMany('App\Models\CarFeature', 'car_features', 'car_id', 'feature_id', 'id', 'id');
    }
}
