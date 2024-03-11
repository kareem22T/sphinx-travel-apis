<?php

namespace App\Models\Tour\Package;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    protected $fillable = [
        "price",
        "package_id",
        "currency_id",
    ];

    protected $table = "package_prices";
    public $timestamps = false;
    
    //Relations 
    public function package()
    {
        return $this->belongsTo('App\Models\Tour\Package\Package', 'package_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id');
    }

}
