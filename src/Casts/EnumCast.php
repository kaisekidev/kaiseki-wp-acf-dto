<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use BackedEnum;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\IterableItemCast;
use Spatie\LaravelData\Casts\Uncastable;
use Spatie\LaravelData\Exceptions\CannotCastEnum;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Throwable;

class EnumCast implements Cast, IterableItemCast
{
    public function __construct(
        protected ?string $type = null
    ) {
    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): BackedEnum|Uncastable
    {
        /** @var class-string<BackedEnum> $type */
        $type = $this->type ?? $property->type->type->findAcceptedTypeForBaseType(BackedEnum::class);

        return $this->castValue(
            $type,
            $value,
            $property->defaultValue instanceof $type ? $property->defaultValue : null
        );
    }

    public function castIterableItem(DataProperty $property, mixed $value, array $properties, CreationContext $context): BackedEnum|Uncastable
    {
        /** @var class-string<BackedEnum> $type */
        $type = $property->type->iterableItemType;

        return $this->castValue(
            $type,
            $value,
            $property->defaultValue instanceof $type ? $property->defaultValue : null
        );
    }

    /**
     * @param class-string<BackedEnum>|null $type
     * @param mixed                         $value
     * @param BackedEnum|null               $default
     *
     * @throws CannotCastEnum
     *
     * @return BackedEnum|Uncastable
     */
    protected function castValue(
        ?string $type,
        mixed $value,
        ?BackedEnum $default = null,
    ): BackedEnum|Uncastable {
        if ($type === null) {
            return Uncastable::create();
        }

        if ($value instanceof $type) {
            return $value;
        }

        /** @var class-string<BackedEnum> $type */
        try {
            // @phpstan-ignore-next-line
            return $type::from($value);
        } catch (Throwable $e) {
            if ($default !== null) {
                return $default;
            }

            throw CannotCastEnum::create($type, $value);
        }
    }
}
