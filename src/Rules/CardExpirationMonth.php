<?php

namespace nickknissen\QuickPay\Rules;

use Illuminate\Contracts\Validation\Rule;

class CardExpirationMonth implements Rule
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
        $firstMonth = 1;
        $lastMonth = 12;
        return is_numeric($value) && $value >= $firstMonth && $value <= $lastMonth;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('The :attribute must be between :min and :max digits.');
    }
}
