<?php

declare(strict_types=1);

namespace Phpolar\Validation;

use ReflectionProperty;

/**
 * Provides a default implementation used to *extract* the value of a property.
 */
abstract class AbstractPropertyValueExtractor
{
    protected mixed $val;

    /**
     * Immutably sets the property value
     *
     * @internal
     */
    public function withPropVal(ReflectionProperty $prop, object $obj): static
    {
        $copy = clone $this;
        $copy->val = $prop->isInitialized($obj) === true ? $prop->getValue($obj) : $prop->getDefaultValue();
        return $copy;
    }
}
