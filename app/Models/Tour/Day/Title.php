<?php

namespace App\Models\Tour\Day;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "day_id",
        "language_id",
    ];

    protected $table = "day_titles";
    public $timestamps = false;
    
    //Relations 
    public function day()
    {
        return $this->belongsTo('App\Models\Day\Day', 'day_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
}
