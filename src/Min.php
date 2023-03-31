<?php

declare(strict_types=1);

namespace Phpolar\Validation;

use Attribute;
use Phpolar\Validator\ValidatorInterface;

/**
 * Provides support for configuring the min value of a property.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Min extends AbstractPropertyValueExtractor implements ValidatorInterface
{
    public function __construct(private int|float $min)
    {
    }

    public function isValid(): bool
    {
        return is_numeric($this->val) === true ? $this->val >= $this->min : true;
    }
}
