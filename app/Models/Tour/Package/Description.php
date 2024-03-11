<?php

namespace App\Models\Tour\Package;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    use HasFactory;
        
    protected $fillable = [
        "description",
        "package_id",
        "language_id",
    ];

    protected $table = "package_descriptions";
    public $timestamps = false;
    
    //Relations 
    public function package()
    {
        return $this->belongsTo('App\Models\Tour\Package\Package', 'package_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
}
