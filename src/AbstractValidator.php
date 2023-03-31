<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Phpolar\Validator\MessageGetterInterface;
use Phpolar\Validator\ValidatorInterface;
use Stringable;

/**
 * A default implementation for retrieving
 * error messages from validators.
 */
abstract class AbstractValidator implements ValidatorInterface, MessageGetterInterface
{
    /**
     * The property's value.
     */
    public mixed $propVal;

    /**
     * An error message that will
     * be displayed if the property
     * is not valid.
     */
    protected string | Stringable $message;

    public function getMessages(): array
    {
        return $this->isValid() === true ? [] : [$this->message];
    }
}
