<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PT Booking Slots Configuration
    |--------------------------------------------------------------------------
    |
    | Define the daily available time slots categorized by period of the day.
    |
    */
    'slots' => [
        'morning' => [
            '07:00',
            '08:00',
            '09:00',
            '10:00',
        ],
        'afternoon' => [
            '13:00',
            '14:00',
            '15:00',
            '16:00',
        ],
        'evening' => [
            '18:00',
            '19:00',
            '20:00',
            '21:00',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Duration & Business Rules
    |--------------------------------------------------------------------------
    |
    | session_duration_minutes: Default session length (e.g. 60 minutes)
    | change_deadline_hours: Minimum hours before session start to allow reschedule/cancel (e.g. 4)
    | late_tolerance_minutes: Display tolerance for member arrival (e.g. 15 minutes)
    |
    */
    'session_duration_minutes' => 60,
    'change_deadline_hours' => 4,
    'late_tolerance_minutes' => 15,
];
