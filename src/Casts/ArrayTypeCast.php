<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Exceptions\CannotCreateCastAttribute;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

use function array_map;
use function is_a;
use function is_array;

class ArrayTypeCast implements Cast
{
    private ?Cast $cast = null;

    public function __construct(
        /** @var class-string<Cast> $castClass */
        ?string $castClass = null,
        mixed ...$arguments
    ) {
        if ($castClass !== null && !is_a($castClass, Cast::class, true)) {
            throw CannotCreateCastAttribute::notACast();
        }

        $this->cast = new $castClass(...$arguments);
    }

    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return array<mixed>
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): array
    {
        return self::castValue($value, $this->cast);
    }

    /**
     * @param mixed $value
     * @param Cast  $cast
     *
     * @return array<mixed>
     */
    public static function castValue(mixed $value, ?Cast $cast = null): array
    {
        if (!is_array($value)) {
            return [];
        }

        if ($cast === null) {
            return $value;
        }

        return array_map(
            fn(mixed $item) => $cast->castValue($item),
            $value
        );
    }
}
