<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Attribute;
use Stringable;

/**
 * Provides support for configuring the max value of a property.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Max extends AbstractValidator
{
    public function __construct(private int|float $max, protected string | Stringable $message = "Value is greater than the maximum")
    {
    }

    public function isValid(): bool
    {
        return is_numeric($this->propVal) === true ? $this->propVal <= $this->max : true;
    }
}
