<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\DataProperty;

use function is_array;
use function is_numeric;

class WpUser implements Castable
{
    public function __construct(
        #[WithCast(ID::class)]
        private readonly int $id,
    ) {
    }

    public function getUserId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?\WP_User
    {
        $user = get_user_by('id', $this->id);
        return $user instanceof \WP_User ? $user : null;
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
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
                if ($value instanceof \WP_User) {
                    return new WpUser($value->ID);
                }
                if (is_array($value) && isset($value['ID'])) {
                    return new WpUser((int)$value['ID']);
                }
                return is_numeric($value) ? new WpUser((int)$value) : null;
            }
        };
    }
}
