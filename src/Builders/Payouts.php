<?php

namespace nickknissen\QuickPay\Builders;

use nickknissen\QuickPay;

class Payouts extends Quickpay
{
    protected $lastResponse;

    protected $currency = 'DKK';

    protected $paymentId;

    protected $card;

    public function getResponse()
    {
        return $this->lastResponse;
    }

    public static function find(int $paymentId)
    {
        $url = sprintf('/payouts/%s', $paymentId);
        $this->lastResponse = $this->request('get', $url);

        $this->paymentId = $this->lastResponse;

        return $this;
    }

    public static function create($orderId, $options = [])
    {
        $this->lastResponse = $this->request('post', '/payouts', array_merge([
            'order_id' => sprintf('%s%s', $this->orderIdPrefix(), $orderId),
            'currency' => $this->currency,
        ], $options));

        $this->paymentId = $this->lastResponse->id;

        return $this;
    }

    public function addCard(Card $card)
    {
        $this->card = $card;
        return $this;
    }

    public function credit(int $amount, $options = [])
    {

        if (!$this->card) {
            throw new \InvalidArgumentException('Missing credit card');
        }

        $url = sprintf('/payouts/%s/credit?synchronized', $this->paymentId);

        $lastResponse = $this->request('post', $url, array_merge(
            [
                'amount' => $amount,
                'card' => $this->card->buildPayload(),
            ],
            $options
        ));

        return $this;
    }
}
