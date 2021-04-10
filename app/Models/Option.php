<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable=['value','display_text','filterId'];

    public function filter(){
        return $this->belongsTo('App\Models\Filter','filterId');
    }
}
