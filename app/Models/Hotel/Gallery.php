<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $fillable = [
        "path",
        "hotel_id",
    ];

    protected $table = "hotel_gallery";
    public $timestamps = false;
    
    //Relations 
    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel\Hotel', 'hotel_id');
    }
}
