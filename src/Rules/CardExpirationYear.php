<?php

namespace nickknissen\QuickPay\Rules;

use Illuminate\Contracts\Validation\Rule;

class CardExpirationYear implements Rule
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
        $currentYear = date('y');
        $maxYear = (date('y') + 50);
        return is_numeric($value) && $value >= $currentYear && $value <= $maxYear;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('quickpay.card.expiration_year', ['current' => date('y'), 'future' => (date('y') + 50)]);
    }
}
