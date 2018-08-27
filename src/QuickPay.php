<?php

namespace nickknissen\QuickPay;

use Illuminate\Support\Facades\App;
use QuickPay\QuickPay as QuickPayVendor;

use nickknissen\QuickPay\Exceptions;

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

    public function request($method, $url, $data = [])
    {
        $response = $this->client->request->$method($url, $data);

        if ($response->isSuccess()) {
            $data = $response->asObject();
            if (App::environment('production') && $data->test_mode) {
                throw new QuickPayTestNotAllowedException();
            }
            return $data;
        } else {
            throw new QuickPayException($response);
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
