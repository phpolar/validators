<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Attribute;
use Stringable;

/**
 * Provides support for configuring the expected pattern of a property.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Pattern extends AbstractValidator
{
    public function __construct(private string $pattern, protected string | Stringable $message = "Pattern validation failed") {}

    public function isValid(): bool
    {
        return isset($this->propVal) === false
            || (is_string($this->propVal)
                && preg_match($this->pattern, $this->propVal) === 1);
    }
}
