<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Exceptions;

use Exception;

class InvalidAttributeType extends Exception
{
    public static function create(string $name, string $type): self
    {
        return new self("Attribute `{$name}` must be of type " . $type);
    }
}
