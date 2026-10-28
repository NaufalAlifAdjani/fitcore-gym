<?php

namespace App\Exceptions;

class SlotUnavailableException extends PtBookingException
{
    public function __construct(string $message = 'Slot waktu yang dipilih sudah terisi atau bentrok.')
    {
        parent::__construct($message);
    }
}
