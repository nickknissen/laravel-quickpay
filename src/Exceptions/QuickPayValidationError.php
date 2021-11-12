<?php

namespace nickknissen\QuickPay\Exceptions;

use QuickPay\API\Response;

class QuickPayValidationError extends \Exception
{
    public function __construct(Response $quickPayResponse)
    {
        $obj = $quickPayResponse->asObject();

        parent::__construct(
            $obj->message,
            $quickPayResponse->httpStatus(),
            null
        );
    }
}
