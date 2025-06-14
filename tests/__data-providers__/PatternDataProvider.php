<?php

declare(strict_types=1);

namespace Phpolar\Validators\Tests\DataProviders;

final class PatternDataProvider
{
    public const EMAIL_PATTERN = "/^[[:alnum:]]+@[[:alnum:]]+\.[[:alpha:]]+$/";

    public const PHONE_PATTERN = "/^\(?[[:digit:]]{3}\)?[[:space:]]?(-|.)?[[:digit:]]{3}(-|.)?[[:digit:]]{4}$/";

    public static function validEmails(): mixed
    {
        return [
            ["test@somewhere.com"],
        ];
    }

    public static function validPhoneNumbers(): mixed
    {
        return [
            ["222-222-2222"],
            ["(222)222-2222"],
            ["(222) 222-2222"],
            ["2222222222"],
        ];
    }

    public static function invalidEmails(): mixed
    {
        return [
            [uniqid()],
            [true],
            [false],
            [""],
            [random_int(PHP_INT_MIN, PHP_INT_MAX)],
        ];
    }
}
