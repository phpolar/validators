<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Attribute;
use Phpolar\Validator\ValidatorInterface;
use ReflectionProperty;

/**
 * Provides support for marking a property as requiring a value.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Required extends AbstractPropertyValueExtractor implements ValidatorInterface
{
    protected mixed $val;

    public function isValid(): bool
    {
        return $this->val !== "" && $this->val !== null;
    }

    public function withPropVal(ReflectionProperty $prop, object $obj): static
    {
        $copy = clone $this;
        $copy->val = $prop->isInitialized($obj) === true ? $prop->getValue($obj) : null;
        return $copy;
    }
}
