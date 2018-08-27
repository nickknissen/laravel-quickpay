<?php

namespace nickknissen\QuickPay\Exceptions;

use QuickPay\API\Response;

class QuickPayException extends \Exception
{
    public function __construct(Response $quickPayResponse)
    {
        parent::__construct(
            $quickPayResponse->asObject()->message,
            $quickPayResponse->httpStatus(),
            null
        );
    }
}
