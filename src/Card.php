<?php

namespace nickknissen\QuickPay;


class Card
{

    protected $number;

    protected $cvd;

    protected $expYear;

    protected $expMonth;

    public function __construct($number, $cvd, $expYear, $expMonth)
    {
        $this->number = $number;
        $this->cvd = $cvd;
        $this->expMonth = $expMonth;
        $this->expYear= $expYear;
    }

    public function getExpiration()
    {
        return '' . $this->expYear . $this->expMonth;
    }

    public function buildPayload()
    {
        return [
            'number' => $this->number,
            'cvd' => $this->cvd,
            'expiration' => $this->getExpiration()
        ];
    }

    public static function fromInput(...$parameters)
    {
        extract(...$parameters);
        return new static ($number, $cvd, $expYear, $expMonth);
    }

}
