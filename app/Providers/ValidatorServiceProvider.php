<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 10;
        });

        Validator::replacer('phone', function ($message, $attribute, $rule, $parameters) {
            if ($message != 'validation.' . $rule)
                return $message;
            return 'Phone number is invalid, please ensure you enter a correct phone number.';
        });

        Validator::extend('postcode', function ($attribute, $value, $parameters, $validator) {
            if (empty($value))
                return true;
            $validation_expression = '/^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][‌​0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9]?[A-Za-z])))) [0-9][A-Za-z]{2})$/';
            return (preg_match($validation_expression, $value) > 0 ? true : false);
        });

        Validator::replacer('postcode', function ($message, $attribute, $rule, $parameters) {
            if ($message != 'validation.' . $rule)
                return $message;
            return 'Post Code format is invalid, please ensure you enter a correct Post Code in the format XX12 3YY';
        });

        Validator::extend('alpha_spaces', function ($attribute, $value) {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('word', function ($attribute, $value, $parameters, $validator) {
            if (!$validator->isAValidFileInstance($value)) {
                return false;
            }
            if (!in_arrayi($value->guessExtension(), ['doc', 'docx'])) {
                return false;
            }
            if (!in_arrayi($value->getClientOriginalExtension(), ['doc', 'docx'])) {
                return false;
            }

            return true;
        });

        Validator::replacer('word', function ($message, $attribute, $rule, $parameters) {
            if ($message != 'validation.' . $rule)
                return $message;
            return "The $attribute field must be of file type .doc, .docx";
        });

        Validator::extend('required_without_except', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();

            if (!empty($data[$parameters[0]]))
                return true;

            array_shift($parameters);
            foreach ($parameters as $param) {
                if (empty($data[$param]))
                    return false;
            }

            return true;
        });
        
        Validator::replacer('required_without_except', function ($message, $attribute, $rule, $parameters) {
            if ($message != 'validation.' . $rule)
                return $message;

            $attribute = str_replace('_', ' ', $attribute);
            $exception = str_replace('_', ' ', array_shift($parameters));
            $params = '';
            foreach ($parameters as $i => $param) {
                $params .= str_replace('_', ' ', $param);
                if ($i + 1 != sizeof($parameters))
                    $params .= ' / ';
            }
            return "The $attribute field is required when none of $params are present. Unless $exception is present";
        });
    }
}
