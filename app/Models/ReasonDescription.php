<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonDescription extends Model
{
    use HasFactory;
    protected $fillable = [
        "description",
        "reason_id",
        "language_id",
    ];

    protected $table = "reason_descriptions";
    public $timestamps = false;
    
    //Relations 
    public function reason()
    {
        return $this->belongsTo('App\Models\Reason', 'reason_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
}
