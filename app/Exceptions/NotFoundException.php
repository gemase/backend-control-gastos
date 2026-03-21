<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class NotFoundException extends AppException
{
    public function __construct(string $message = 'Recurso no encontrado.')
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND);
    }
}
