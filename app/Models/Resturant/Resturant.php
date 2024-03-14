<?php

namespace App\Models\Resturant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resturant extends Model
{
    use HasFactory;
                
    protected $fillable = [
        "thumbnail",
        "address",
        "address_name",
        "lat",
        "lng",
    ];

    protected $table = "resturants";
    public $timestamps = false;
    
    //Relations 
    public function titles()
    {
        return $this->hasMany('App\Models\Resturant\Title', 'resturant_id');
    }

    public function descriptions()
    {
        return $this->hasMany('App\Models\Resturant\Description', 'resturant_id');
    }
}
