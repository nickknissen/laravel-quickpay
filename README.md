# Laravel Quickpay

Wrapper around [quickpay/quickpay-php-client](https://github.com/QuickPay/quickpay-php-client)


## TODO
* Handle payout 
* Handle cards/token
* Handle subscriptions 
* Migrations/Model for quickpay response data
* Callback/Webhook controller
* Create payment/payout builder (fluent api)


## Current api
none-production payments are prefix with E + first letter of current environment. See [QuickPay@orderIdPrefix](./src/QuickPay.php#L51)  
Ex. order id 12345 becomes `El12345` when `APP_ENV=local` and `ES12345` for `APP_ENV=staging`

```php 
use nickknissen\QuickPay\Card;
use nickknissen\QuickPay\Payments;

$orderId = 12345; 
$amount = 1000; // amount in its smallest unit (cents/øre)

$card = new Card(1000000000000008, 123, 20, 11);

$payments = new Payments();
$payment = $payments->create($orderId);

$authorized = $payments->authorize($payment->id, $amount, $card);
$captured = $payments->capture($payment->id, $amount);
```


## Idea for future api
```php 
$orderId = 12345;
$amount = 1000;

use nickknissen\QuickPay\Card;
use nickknissen\QuickPay\Payment;
use nickknissen\QuickPay\Payout;

$card = new Card($number, $cvd, $exp_year, $exp_month);

$payment = Payment::create($orderId)
    ->authorize($amount, $card)
    ->capture();

if ($payment->accepted) {
    echo "sucess";
}

$payment = Payment::find($paymentId)
    ->cancel();

$payment = Payment::find($paymentId)
    ->refund();

$card = Card::create()
    ->authorize($number, $cvd, $exp_year, $exp_month);

$card = Payout::create($orderId)
    ->credit($amount, $card)
```

