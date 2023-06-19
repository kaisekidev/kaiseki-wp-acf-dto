<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Exceptions;

use Exception;

class MissingAttribute extends Exception
{
    public static function castMissingAttribute(string $name): self
    {
        return new self("WithCast attribute `{$name}` is missing");
    }

    public static function castableMissingAttribute(string $name): self
    {
        return new self("WithCastable attribute `{$name}` is missing");
    }
}
