<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarFeatureName extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "feature_id",
        "language_id",
    ];

    protected $table = "carfeature_names";
    public $timestamps = false;
    
    //Relations 
    public function feature()
    {
        return $this->belongsTo('App\Models\CarFeature', 'feature_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
}
