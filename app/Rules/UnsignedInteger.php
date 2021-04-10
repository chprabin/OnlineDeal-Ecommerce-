<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UnsignedInteger implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $pattern="";
    public function __construct($with_zero=false)
    {
      if($with_zero){
        $this->pattern="/^0$^[1-9](\d){0,10}$/";
      }else{
        $this->pattern="/^[1-9](\d){0,10}$/";
      }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

    public function passes($attribute, $value)
    {
        if(preg_match($this->pattern, $value)){
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribut should be unsigned integer';
    }
}
