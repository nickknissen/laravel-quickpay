<?php

namespace nickknissen\QuickPay\Exceptions;

use QuickPay\API\Response;

class QuickPayValidationError extends \Exception
{
    public function __construct(Response $quickPayResponse)
    {
        $obj = $quickPayResponse->asObject();
        $errorMessage = sprintf('%s %s', key($obj->errors),reset($obj->errors)[0]);

        parent::__construct(
            $errorMessage,
            $quickPayResponse->httpStatus(),
            null
        );
    }
}
