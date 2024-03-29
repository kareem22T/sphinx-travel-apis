<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarFeature extends Model
{
    use HasFactory;
    protected $fillable = [
        "icon_path",
    ];

    protected $table = "carfeatures";
    public $timestamps = false;

    // Relations
    public function cars()
    {
        return $this->belongsToMany('App\Models\Car\Car', 'car_features', 'car_id', 'feature_id', 'id', 'id');
    }

    public function names()
    {
        return $this->hasMany('App\Models\CarFeatureName', 'feature_id');
    }    
}
