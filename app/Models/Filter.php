<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $fillable=['name','type','display_text','url_slug'];
    
    public function options(){
        return $this->hasMany('App\Models\Option','filterId');
    }

    public function addOptions(array $options){
        foreach($options as $option){
            $option=(array)$option;
            try{
                $this->options()->create($option);
            }catch(\Exception $ex){
                continue;
            }
        }
    }
}
