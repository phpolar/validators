<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Attribute;
use ReflectionProperty;
use Stringable;

/**
 * Provides support for marking a property as requiring a value.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Required extends AbstractValidator
{
    public function __construct(protected string | Stringable $message = "Required value")
    {
    }

    public function isValid(): bool
    {
        return $this->propVal !== "" && $this->propVal !== null;
    }

    /**
     * Validation of required property's
     * requires that the property is not set or set
     * to null.
     */
    public function withRequiredPropVal(ReflectionProperty $prop, object $obj): static
    {
        $copy = clone $this;
        $copy->propVal = $prop->isInitialized($obj) === true ? $prop->getValue($obj) : null;
        return $copy;
    }
}
