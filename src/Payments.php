<?php

namespace nickknissen\QuickPay;


class Payments extends Quickpay
{
    public function create($orderId, $options = [])
    {
        $payment = $this->client->request->post('/payments', array_merge([
            'order_id' => sprintf('%s%s', $this->orderIdPrefix(), $orderId),
            'currency' => $this->currency,
        ], $options));

        if ($payment->isSuccess()) {
            return $payment->asObject();
        } else {
            throw new QuickPayException($payment);
        }
    }

    public function authorize(int $paymentId, int $amount, Card $card, $options = [])
    {
        $url = sprintf("/payments/%s/authorize?synchronized", $paymentId);

        $payment = $this->client->request->post($url, array_merge([
            'amount' => $amount,
            'card' => $card->getInfo(),
        ], $options));

        if ($payment->isSuccess()) {
            return $payment->asObject();
        } else {
            throw new QuickPayException($payment);
        }
    }

    public function capture(int $paymentId, int $amount, $options = [])
    {
        $url = sprintf("/payments/%s/capture?synchronized", $paymentId);

        $payment = $this->client->request->post($url, array_merge([
            'amount' => $amount,
        ], $options));

        if ($payment->isSuccess()) {
            return $payment->asObject();
        } else {
            throw new QuickPayException($payment);
        }
    }

    public function cancel(int $paymentId)
    {
        $url = sprintf("/payments/%s/cancel", $paymentId);

        $payment = $this->client->request->post($url);

        if ($payment->isSuccess()) {
            return $payment->asObject();
        } else {
            throw new QuickPayException($payment);
        }
    }

    public function renew(int $paymentId)
    {
        $url = sprintf("/payments/%s/renew", $paymentId);

        $payment = $this->client->request->post($url);

        if ($payment->isSuccess()) {
            return $payment->asObject();
        } else {
            throw new QuickPayException($payment);
        }
    }
}
