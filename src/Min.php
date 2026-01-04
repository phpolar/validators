<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Attribute;
use PhpContrib\Validator\MessageGetterInterface;
use PhpContrib\Validator\ValidatorInterface;
use Stringable;

/**
 * Provides support for configuring the min value of a property.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Min implements ValidatorInterface, MessageGetterInterface
{
    /**
     * The property's value.
     */
    public mixed $propVal;

    public function __construct(private int|float $min, protected string | Stringable $message = "Value is less than the minimum") {}

    public function isValid(): bool
    {
        return isset($this->propVal) === true && is_numeric($this->propVal) === true ? $this->propVal >= $this->min : true;
    }


    public function getMessage(): string
    {
        return $this->isValid() === true ? "" : (string) $this->message;
    }
}
