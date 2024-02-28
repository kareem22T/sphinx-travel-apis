<?php

namespace App\Models\Tour\Day;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
            
    protected $fillable = [
        "thumbnail",
        "tour_id",
    ];

    protected $table = "tour_days";
    public $timestamps = false;
    
    //Relations 
    public function tour()
    {
        return $this->belongsTo('App\Models\Tour\Tour', 'tour_id');
    }
    public function titles()
    {
        return $this->hasMany('App\Models\Dya\Title', 'day_id');
    }


}
