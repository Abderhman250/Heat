<?php

namespace App\Rules;

use App\Lookup;
use Illuminate\Contracts\Validation\Rule;

class LookupRule implements Rule
{
    private $attribute ;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
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
        //
        $this->attribute = $attribute;
        return Lookup::where('column_id', $attribute)
                      ->where('id', $value)
                      ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation id '.$this->attribute.".";
    }
}
