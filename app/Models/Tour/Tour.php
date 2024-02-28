<?php

namespace App\Models\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;
    protected $fillable = [
        "depature",
        "duration",
        "min_participant",
        "max_participant"
    ];

    public $table = "tours";

    // Relations
    public function titles()
    {
        return $this->hasMany('App\Models\Tour\Title', 'tour_id');
    }

    public function intros()
    {
        return $this->hasMany('App\Models\Tour\Intro', 'tour_id');
    }

    public function locations()
    {
        return $this->hasMany('App\Models\Tour\Location', 'tour_id');
    }

    public function transportations()
    {
        return $this->hasMany('App\Models\Tour\Transportation', 'tour_id');
    }

    public function descriptions()
    {
        return $this->hasMany('App\Models\Tour\Description', 'tour_id');
    }

    public function includes()
    {
        return $this->hasMany('App\Models\Tour\IncludeModel', 'tour_id');
    }

    public function excludes()
    {
        return $this->hasMany('App\Models\Tour\Exclude', 'tour_id');
    }

    public function days()
    {
        return $this->hasMany('App\Models\Tour\Day', 'tour_id');
    }

    public function gallery()
    {
        return $this->hasMany('App\Models\Tour\Gallery', 'tour_id');
    }
    

}
