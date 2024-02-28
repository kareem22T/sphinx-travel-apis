<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    use HasFactory;
    protected $fillable = [
        "icon_path",
        "hotel_id",
    ];

    protected $table = "reasons";
    public $timestamps = false;

    // Relations
    public function hotels()
    {
        return $this->belongsToMany('App\Models\Hotel\Hotel', 'hotel_reasons', 'hotel_id', 'reason_id', 'id', 'id');
    }

    public function names()
    {
        return $this->hasMany('App\Models\ReasonName', 'reason_id');
    }    
    public function descriptions()
    {
        return $this->hasMany('App\Models\ReasonDescription', 'reason_id');
    }    
}
