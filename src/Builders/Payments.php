<?php

namespace nickknissen\QuickPay\Builders;

class Payments extends Quickpay
{

    protected $lastResponse;

    protected $currency = 'DKK';

    protected $paymentId;

    protected $card;

    public function getResponse()
    {
        return $this->lastResponse;
    }

    public function addCard(Card $card)
    {
        $this->card = $card;
        return $this;
    }

    public function find(int $paymentId)
    {
        $url = sprintf('/payments/%s', $paymentId);
        $this->lastResponse = $this->request('get', $url);
        $this->paymentId = $this->lastResponse;

        return $this;

    }

    public function create($orderId, $options = [])
    {
        $this->lastResponse = $this->request('post', '/payments', array_merge([
            'order_id' => sprintf('%s%s', $this->orderIdPrefix(), $orderId),
            'currency' => $this->currency,
        ], $options));
        $this->paymentId = $this->lastResponse;

        return $this;
    }

    public function authorize(int $amount, array $options = [])
    {
        if (!$this->card) {
            throw new \InvalidArgumentException('Missing credit card');
        }

        if (!$this->paymentId) {
            throw new \InvalidArgumentException('Missing payment id. Call find or create before authorize');
        }
        $url = sprintf('/payments/%s/authorize?synchronized', $this->paymentId);

        $lastResponse = $this->request('post', $url, array_merge([
            'amount' => $amount,
            'card' => $this->card->buildPayload(),
        ], $options));

        return $this;
    }

    public function capture(int $amount, $options = [])
    {
        if (!$this->paymentId) {
            throw new \InvalidArgumentException('Missing payment id. Call find or create before capture');
        }
        $url = sprintf('/payments/%s/capture?synchronized', $this->paymentId);

        return $this->request('post', $url, array_merge([
            'amount' => $amount,
        ], $options));
    }

    public function cancel(int $paymentId)
    {
        if (!$this->paymentId) {
            throw new \InvalidArgumentException('Missing payment id. Call find or create before capture');
        }
        $url = sprintf('/payments/%s/cancel', $this->paymentId);

        $this->lastResponse = $this->request('post', $url);
        return $this;
    }

    public function renew()
    {
        if (!$this->paymentId) {
            throw new \InvalidArgumentException('Missing payment id. Call find or create before capture');
        }
        $url = sprintf('/payments/%s/renew', $this->paymentId);

        $this->lastResponse = $this->request('post', $url);
        return $this;
    }
}


