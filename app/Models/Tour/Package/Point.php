<?php

namespace App\Models\Tour\Package;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    protected $fillable = [
        "package_id",
    ];

    protected $table = "package_points";
    public $timestamps = false;
    
    //Relations 
    public function package()
    {
        return $this->belongsTo('App\Models\Tour\Package\Package', 'package_id');
    }
    public function titles()
    {
        return $this->hasMany('App\Models\Tour\Package\Point_titles', 'point_id');
    }

    public function descriptions()
    {
        return $this->hasMany('App\Models\Tour\Package\Point_descriptions', 'point_id');
    }


}
