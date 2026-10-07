<?php

namespace App\Exceptions;

class ChangeDeadlinePassedException extends PtBookingException
{
    public function __construct(string $message = 'Batas waktu perubahan atau pembatalan jadwal telah terlewati (minimal 4 jam sebelum sesi).')
    {
        parent::__construct($message);
    }
}
