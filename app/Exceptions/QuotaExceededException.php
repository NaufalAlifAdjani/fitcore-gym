<?php

namespace App\Exceptions;

class QuotaExceededException extends PtBookingException
{
    public function __construct(string $message = 'Kuota sesi PT Anda telah habis atau tidak memiliki paket aktif.')
    {
        parent::__construct($message);
    }
}
