<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\DataObjects\WpUser;
use Spatie\LaravelData\Attributes\GetsCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use WP_User;

use function is_array;
use function is_numeric;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpUserCast implements GetsCast
{
    public function get(): Cast
    {
        return new class implements Cast {
            /**
             * @param DataProperty $property
             * @param mixed        $value
             * @param array<mixed> $context
             *
             * @return WpUser|null
             */
            public function cast(DataProperty $property, mixed $value, array $context): ?WpUser
            {
                $userId = WpUserCast::getUserId($value);
                return $userId !== null ? new WpUser($userId) : null;
            }
        };
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