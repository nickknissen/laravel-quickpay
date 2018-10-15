<?php

namespace nickknissen\QuickPay;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $hidden = [
        'quickpay_card_id',
    ];

    protected $fillable = [
        'number', 'cvd', 'expiration'
    ];

    /**
     * Don't save full number in the database
     */
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $unsavable = ['number', 'cvd', 'expiration'];
            if (count($unsavable) > 0) {
                $model->attributes = array_diff_key($model->attributes, array_flip($unsavable));
            }
        });
    }

    public function setNumberAttribute(number $value)
    {
        $this->attributes['number'] = $value;
        $this->attributes['last_4_digets'] = substr($value, -4);
        $this->attributes['type'] = $this->detectType($value);
    }

    public function detectType(number $number) : string
    {
        $res = [
            'Electron' => '/^(4026|417500|4405|4508|4844|4913|4917)\d+$/',
            'Maestro' => '/^(5018|5020|5038|5612|5893|6304|6759|6761|6762|6763|0604|6390)\d+$/',
            'Dankort' => '/^(5019)\d+$/',
            'Interpayment' => '/^(636)\d+$/',
            'Unionpay' => '/^(62|88)\d+$/',
            'Visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'Mastercard' => '/^5[1-5][0-9]{14}$/',
            'Amex' => '/^3[47][0-9]{13}$/',
            'Diners' => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
            'Discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            'JCB' => '/^(?:2131|1800|35\d{3})\d{11}$/',
            'Test' => '/^(1000)\d+$/',
        ];

        foreach ($res as $t => $re) {
            if (preg_match($re, $number) === 1) {
                return $t;
            }
        }

        throw new \Exception('Could not determine card type');
    }

    public function buildPayload(): array
    {
        if ($this->quickpay_card_id) {
            $qb = new Quickpay();
            $card = $qb->request(
                'post',
                sprintf('/cards/%s/tokens', $this->quickpay_card_id)
            );

            return ['token' => $card->token, ];
        } else {
            return [
                'number' => $this->number,
                'cvd' => $this->cvd,
                'expiration' => $this->expiration,
            ];
        }
    }

    public function createAsQuickpayCard($userId): Card
    {
        $qb = new Quickpay();
        $cardId = $qb->request('post', '/cards')->id;

        $card = $qb->request(
            'post',
            sprintf('/cards/%s/authorize?synchronized', $cardId),
            [
                'card' => [
                    'number' => $this->attributes['number'],
                    'expiration' => $this->attributes['expiration'],
                    'cvd' => $this->attributes['cvd'],
                ],
                'acquirer' => 'clearhaus',
            ]
        );

        $this->quickpay_card_id = $cardId;
        $this->user_id = $userId;
        $this->save();

        return $this;
    }

    public function asQuickpayCard() : object
    {
        $qb = new Quickpay();
        return $qb->request('get', sprintf('/cards/%s', $this->quickpay_card_id));
    }
}
