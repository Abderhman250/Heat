<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class ValidUser implements Rule
{
    protected $status ,$attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($status = 1)
    {
        $this->status = $status;
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
        $this->attribute =  $attribute;
        // Check if a user with the given phone number and status exists
        return User::where([$attribute=> $value,  'is_active' =>true])->exists();
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The '.$this->attribute.' is not valid or the user is not active.';
    }
}
