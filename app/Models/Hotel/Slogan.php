<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slogan extends Model
{
    use HasFactory;

    protected $fillable = [
        "slogan",
        "hotel_id",
        "language_id",
    ];

    protected $table = "hotel_slogans";
    public $timestamps = false;

    //Relations 
    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel\Hotel', 'hotel_id');
    }
        
}
