<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        "address",
        "hotel_id",
        "language_id",
    ];

    protected $table = "hotel_addresses";
    public $timestamps = false;
    
    //Relations 
    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel\Hotel', 'hotel_id');
    }

}
