<?php

namespace nickknissen\QuickPay;

use QuickPay\QuickPay as QuickPayVendor;


class Quickpay
{

    protected $client;

    protected $currency  = 'DKK';

    public function __construct()
    {
        $credentials = null;

        if (config('quickpay.api_key')) {
            $credentials = ":".config('quickpay.api_key');
        }

        $this->client = new QuickPayVendor($credentials);
    }


    public function find($paymentId)
    {
        $url = sprintf("/payments/%s", $paymentId);

        $payment = $this->client->request->get($url);

        if ($payment->isSuccess()) {
            return $payment->asObject();
        } else {
            throw new QuickPayException($payment);
        }
    }

    public function orderIdPrefix()
    {
        if (!str_contains(strtolower(config('app.env')), 'prod')) {
            return '';
        }

        return  'E' . mb_substr(config('app.env'), 0, 1);
    }
}
