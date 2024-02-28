<?php

namespace App\Models\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
            
    protected $fillable = [
        "location",
        "tour_id",
        "language_id",
    ];

    protected $table = "tour_locations";
    public $timestamps = false;
    
    //Relations 
    public function tour()
    {
        return $this->belongsTo('App\Models\Tour\Tour', 'tour_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
}
