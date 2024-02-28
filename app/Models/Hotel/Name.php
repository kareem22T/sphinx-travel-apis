<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Name extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "hotel_id",
        "language_id",
    ];

    protected $table = "hotel_names";
    public $timestamps = false;
    
    //Relations 
    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel\Hotel', 'hotel_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }

}
