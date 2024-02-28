<?php

namespace App\Models\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intro extends Model
{
    use HasFactory;
        
    protected $fillable = [
        "intro",
        "tour_id",
        "language_id",
    ];

    protected $table = "tour_intros";
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
