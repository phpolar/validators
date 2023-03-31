<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Attribute;
use Stringable;

/**
 * Provides support for configuring the max length of a property.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class MaxLength extends AbstractValidator
{
    public function __construct(private int $maxLen, protected string | Stringable $message = "Maximum length validation failed")
    {
    }

    public function isValid(): bool
    {
        return $this->maxLen >= match (true) {
            is_string($this->propVal) => mb_strlen($this->propVal),
            is_int($this->propVal) => strlen(strval(abs($this->propVal))),
            default => $this->maxLen,
        };
    }
}
