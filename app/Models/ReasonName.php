<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonName extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "reason_id",
        "language_id",
    ];

    protected $table = "reason_names";
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
