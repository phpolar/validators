<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Attribute;
use Stringable;

/**
 * Provides support for marking a property as requiring a value.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Required extends AbstractValidator
{
    public function __construct(protected string | Stringable $message = "Required value") {}

    public function isValid(): bool
    {
        return isset($this->propVal) === true && ($this->propVal === false || $this->propVal === 0 || empty($this->propVal) === false);
    }
}
