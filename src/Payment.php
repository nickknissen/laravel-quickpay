<?php

namespace nickknissen\QuickPay;

class Payment extends QuickPay
{
    public function find(int $paymentId): object
    {
        $url = sprintf('/payments/%s', $paymentId);
        return $this->request('get', $url);
    }

    public function create(string $orderId, string $currency, array $options = []): object
    {
        return $this->request('post', '/payments', array_merge([
            'order_id' => sprintf('%s%s', $this->orderIdPrefix(), $orderId),
            'currency' => $currency,
        ], $options));
    }

    public function link(string $paymentId, int $amount, array $options = []): object
    {
        $url = sprintf('/payments/%s/link', $paymentId);
        return $this->request('put', $url, array_merge([
            'amount' => $amount
        ], $options));
    }

    public function authorize(int $paymentId, int $amount, Card $card, array $options = []): object
    {
        $url = sprintf('/payments/%s/authorize?synchronized', $paymentId);

        return $this->request('post', $url, array_merge([
            'amount' => $amount,
            'card' => $card->buildPayload(),
        ], $options));
    }

    public function capture(int $paymentId, int $amount, array $options = []): object
    {
        $url = sprintf('/payments/%s/capture?synchronized', $paymentId);

        return $this->request('post', $url, array_merge([
            'amount' => $amount,
        ], $options));
    }

    public function cancel(int $paymentId): object
    {
        $url = sprintf('/payments/%s/cancel', $paymentId);

        return $this->request('post', $url);
    }

    public function renew(int $paymentId): object
    {
        $url = sprintf('/payments/%s/renew', $paymentId);

        return $this->request('post', $url);
    }
}
