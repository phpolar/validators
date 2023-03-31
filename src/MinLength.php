<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Attribute;
use Stringable;

/**
 * Provides support for configuring the minimum length of a property.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class MinLength extends AbstractValidator
{
    public function __construct(private int $minLen, protected string | Stringable $message = "Minimum length validation failed")
    {
    }

    public function isValid(): bool
    {
        return $this->minLen <= match (true) {
            is_string($this->propVal) => mb_strlen($this->propVal),
            is_int($this->propVal) => strlen(strval(abs($this->propVal))),
            default => $this->minLen,
        };
    }
}
