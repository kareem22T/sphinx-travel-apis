<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $fillable = [
        "icon_path",
    ];

    protected $table = "features";
    public $timestamps = false;

    // Relations
    public function hotels()
    {
        return $this->belongsToMany('App\Models\Hotel\Hotel', 'hotel_features', 'hotel_id', 'feature_id', 'id', 'id');
    }

    public function names()
    {
        return $this->hasMany('App\Models\FeatureName', 'feature_id');
    }    
}
