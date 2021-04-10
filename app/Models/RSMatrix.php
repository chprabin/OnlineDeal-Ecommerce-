<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RSMatrix extends Model
{
    protected $fillable=['itemId1','itemId2','sum','count'];
    protected $table='rs_matrix';
}
