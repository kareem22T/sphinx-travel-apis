<?php

namespace App\Models\Tour\Package;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point_titles extends Model
{
    use HasFactory;
        
    protected $fillable = [
        "title",
        "point_id",
        "language_id",
    ];

    protected $table = "point_titles";
    public $timestamps = false;
    
    //Relations 
    public function point()
    {
        return $this->belongsTo('App\Models\Tour\Package\Point', 'point_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
}
