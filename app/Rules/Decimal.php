<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Decimal implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $pattern='';
    public function __construct($integral, $points)
    {   
        if($points < 1){
            $points=1;
        }
        $this->pattern="/^(\d){0,$integral}\.(\d){0,$points}$/";
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
        return ':attribute must be a decimal number';
    }
}
