<?php

namespace nickknissen\QuickPay;

class Payouts extends Quickpay
{
    public function find(int $paymentId): object
    {
        $url = sprintf('/payouts/%s', $paymentId);
        return $this->request('get', $url);
    }

    public function create(string $orderId, array $options = []): object
    {
        return $this->request('post', '/payouts', array_merge([
            'order_id' => sprintf('%s%s', $this->orderIdPrefix(), $orderId),
            'currency' => $this->currency,
        ], $options));
    }

    public function credit(int $paymentId, int $amount, Card $card, array $options = []): object
    {
        $url = sprintf('/payouts/%s/credit?synchronized', $paymentId);

        return $this->request('post', $url, array_merge(
            ['amount' => $amount, 'card' => $card->buildPayload()],
            $options
        ));
    }
}
