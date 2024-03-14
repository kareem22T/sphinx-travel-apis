<?php

namespace App\Models\Resturant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    use HasFactory;
    protected $fillable = [
        "description",
        "resturant_id",
        "language_id",
    ];

    protected $table = "resturant_descriptions";
    public $timestamps = false;
    
    //Relations 
    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant\Resturant', 'resturant_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }

}
