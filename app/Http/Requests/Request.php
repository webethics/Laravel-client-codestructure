<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    protected function parseDateInput($date)
    {
        if ($date instanceof Carbon) {
            return $date;
        }

        try {
            return Carbon::createFromFormat(config('settings.date_format'), $date);
        } catch (\InvalidArgumentException $e) {
            return null;
        }
    }
}
