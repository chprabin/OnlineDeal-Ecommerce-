<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoSpecialCharacter implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     * 
     */
    public $pattern="/^([-a-zA-Z0-9;:,#&\.\/ ])+$/i";
    public function __construct()
    {
        //
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
        $result=false;
        if(preg_match($this->pattern, $value)){
            $result=true;
        }
        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute must not contain any special character';
    }
}
