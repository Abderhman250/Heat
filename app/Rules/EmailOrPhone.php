<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailOrPhone implements Rule
{
    protected $otherField;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
 

    public function __construct($otherField)
    {
        $this->otherField = $otherField;
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
        // Check if the other field is filled
        return !request()->filled($this->otherField);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot provide both ' . $this->otherField . ' and ' . request()->input($this->otherField) . '.';
    }
}
