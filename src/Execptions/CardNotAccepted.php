<?php

namespace nickknissen\QuickPay\Exceptions;

use QuickPay\API\Response;

class CardNotAccepted extends \Exception
{
    public function __construct($data)
    {
        $errorMessage = '';
        if (!$data->accepted) {
            $errorMessage = sprintf("%s not accepted", $data->type);
        }
        parent::__construct(
            $errorMessage,
            $data->operations[0]->qp_status_code,
            null
        );
    }
}
