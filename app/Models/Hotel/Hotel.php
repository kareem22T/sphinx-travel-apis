<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Resturant\Resturant;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "phone",
        "type",
        "check_in",
        "check_out",
        "address",
        "address_name",
        "lat",
        "lng",
        "avg_rating",
        "num_of_ratings",
        "avg_staff_rating",
        "avg_facilities_rating",
        "avg_cleanliness_rating",
        "avg_comfort_rating",
        "avg_money_rating",
        "avg_location_rating",
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
    public function tours()
    {
        return $this->belongsToMany('App\Models\Tour\Tour', 'hotel_tours', 'hotel_id', 'tour_id', 'id', 'id');
    }

    public function nearestRestaurants($limit = 10, $maxDistance = 10, $lang)
    {
        $haversine = "(6371 * acos(cos(radians($this->lat)) * cos(radians(lat)) * cos(radians(lng) - radians($this->lng)) + sin(radians($this->lat)) * sin(radians(lat))))";

        return Resturant::select('*')
            ->with(["titles" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            }, "descriptions"  => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            }])
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} <= ?", [$maxDistance])
            ->orderBy('distance', 'asc')
            ->take($limit)
            ->get();
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\Hotel_rating', 'hotel_id');
    }

}
