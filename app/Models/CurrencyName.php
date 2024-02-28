<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyName extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "currency_id",
        "language_id",
    ];

    protected $table = "currency_names";
    public $timestamps = false;
    
    //Relations 
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
}
