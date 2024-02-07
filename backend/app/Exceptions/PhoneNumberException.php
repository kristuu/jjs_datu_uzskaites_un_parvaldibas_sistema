<?php

namespace App\Exceptions;

use Propaganistas\LaravelPhone\Exceptions\NumberParseException as BaseNumberParseException;

class PhoneNumberException extends BaseNumberParseException
{
    public static function countryRequired($number)
    {
        return new static(trans('validation.exceptions.country_required'), $number);
    }

    public static function countryMismatch($number, $countries)
    {
        $countries = array_filter(is_array($countries) ? $countries : [$countries]);

        return new static(trans('validation.exceptions.country_mismatch'), $number, $countries);
    }
}
