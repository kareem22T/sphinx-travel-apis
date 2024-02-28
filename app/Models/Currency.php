<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $fillable = [
        "code",
        "name"
    ];

    public $table = "currencies";

    public function names()
    {
        return $this->hasMany('App\Models\CurrencyName', 'currency_id');
    }    
}
