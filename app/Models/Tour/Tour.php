<?php

namespace App\Models\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;
    protected $fillable = [
        "expired_date",
        "duration",
        "min_participant",
        "max_participant",
        "avg_rating",
        "num_of_ratings",
        "destination_id",
        "lowest_package_price",
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
        return $this->hasMany('App\Models\Tour\Day\Day', 'tour_id');
    }

    public function packages()
    {
        return $this->hasMany('App\Models\Tour\Package\Package', 'tour_id');
    }

    public function gallery()
    {
        return $this->hasMany('App\Models\Tour\Gallery', 'tour_id');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\Tour_rating', 'tour_id');
    }

    public function destination()
    {
        return $this->belongsTo('App\Models\Destination', 'destination_id');
    }


}
