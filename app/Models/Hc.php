<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hc extends Model
{
    protected $fillable=['name','content','section'];
    protected $table='hcs';
}
