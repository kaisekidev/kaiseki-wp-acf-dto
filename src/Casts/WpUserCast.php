<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Castables\WpUser;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use WP_User;

use function is_array;
use function is_numeric;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpUserCast implements Cast
{
    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return WpUser|null
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?WpUser
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): ?WpUser
    {
        $userId = self::getUserId($value);

        return $userId !== null ? new WpUser(
            $userId,
            $value instanceof WP_User ? $value : null,
        ) : null;
    }

    public static function getUserId(mixed $value): ?int
    {
        if (is_numeric($value)) {
            return (int)$value;
        }
        if (is_array($value) && isset($value['ID'])) {
            return (int)$value['ID'];
        }

        return $value instanceof WP_User ? $value->ID : null;
    }
}
