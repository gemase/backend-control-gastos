<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class BusinessException extends AppException
{
    public function __construct(string $message)
    {
        parent::__construct($message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
