<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "phone",
        "map",
        "check_in",
        "check_out",
    ] ;

    public $table = "hotels";

    // Relations
    public function names()
    {
        return $this->hasMany('App\Models\Hotel\Name', 'hotel_id');
    }

    public function slogans()
    {
        return $this->hasMany('App\Models\Hotel\Slogan', 'hotel_id');
    }

    public function descriptions()
    {
        return $this->hasMany('App\Models\Hotel\Description', 'hotel_id');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Hotel\Address', 'hotel_id');
    }

    public function gallery()
    {
        return $this->hasMany('App\Models\Hotel\Gallery', 'hotel_id');
    }
    
    public function rooms()
    {
        return $this->hasMany('App\Models\Hotel\Rooms\Room', 'hotel_id');
    }

    public function reasons()
    {
        return $this->hasMany('App\Models\Reason', 'hotel_id');
    }

    public function features()
    {
        return $this->belongsToMany('App\Models\Feature', 'hotel_features', 'hotel_id', 'feature_id', 'id', 'id');
    }
}
