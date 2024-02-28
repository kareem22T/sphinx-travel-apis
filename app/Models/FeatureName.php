<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureName extends Model
{
        use HasFactory;
        protected $fillable = [
            "name",
            "feature_id",
            "language_id",
        ];

        protected $table = "feature_names";
        public $timestamps = false;
        
        //Relations 
        public function feature()
        {
            return $this->belongsTo('App\Models\Feature', 'feature_id');
        }

        public function language()
        {
            return $this->belongsTo('App\Models\Language', 'language_id');
        }

}
