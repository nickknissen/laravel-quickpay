# Laravel Quickpay

Wrapper around [quickpay/quickpay-php-client](https://github.com/QuickPay/quickpay-php-client)

## Installation
``` bash
composer require nickknissen/laravel-quickpay

php artisan vendor:publish --provider="nickknissen\QuickPay\QuickPayServiceProvider"

#Create card migration
php artisan migrate
```


## TODO before v1
* Callback/Webhook controller
* Create payment/payout builder (fluent api/chaining)
* Refactor card class
* Optional `synchronized` parameter


## Current api
none-production payments are prefix with E + first letter of current environment. See [QuickPay@orderIdPrefix](./src/QuickPay.php#L51)  
Ex. order id 12345 becomes `El12345` when `APP_ENV=local` and `ES12345` for `APP_ENV=staging`

```repl
>> use nickknissen\QuickPay\Card;
>> use nickknissen\QuickPay\Payments;

>> $orderId = 12345; 
>> $userId = 12345; 
>> $amount = 1000; // amount in its smallest unit (cents/øre)

>> $card = new Card(['number' => 1000000000000008, 'expiration' => 2012, 'cvd' => 123]);
=> nickknissen\QuickPay\Card {#2951
     number: 1000000000000008,
     last_4_digets: "7569",
     type: "Test",
     expiration: 2010,
     cvd: 123,
   }

>> $card->createAsQuickPayCard($userId);
=> nickknissen\QuickPay\Card {#2974
     last_4_digets: "0008",
     type: "Test",
     user_id: 1,
     updated_at: "2018-09-06 07:51:46",
     created_at: "2018-09-06 07:51:46",
     id: 5,
   }

>> $payments = new Payments();
>> $payment = $payments->create($orderId);
=> {#2950
     +"id": 124473005,
     +"order_id": "12345",
     +"accepted": false,
     +"type": "Payment",
     ....
   }

>> $authorized = $payments->authorize($payment->id, $amount, $card);

=> {#2974
     +"id": 124473005,
     +"order_id": "12345",
     +"accepted": true,
     +"type": "Payment",
     ....
    +"operations": [
      {#2971
         +"id": 1,
         +"type": "authorize",
      ...
      },
   }
>> $captured = $payments->capture($payment->id, $amount);
