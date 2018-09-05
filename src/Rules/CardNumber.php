<?php

namespace nickknissen\QuickPay\Rules;

use Illuminate\Contracts\Validation\Rule;

class CardNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^[0-9]{14,19}$/', $value) !== false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return tran('The :attribute is not a valid card number');
    }
}
