<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour_rating extends Model
{
    use HasFactory;

    protected $fillable = [
        "rate",
        "describe",
        "approved",
        "tour_id",
        "user_id",
    ];

    protected $table = "tour_rating";

    //Relations
    public function tour()
    {
        return $this->belongsTo('App\Models\Tour\Tour', 'tour_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }


}
