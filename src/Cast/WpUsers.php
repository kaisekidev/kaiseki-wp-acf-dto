<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Cast;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\DataProperty;

use function array_map;

class WpUsers implements Castable
{
    public function __construct(
        /** @var array<int> */
        private readonly array $ids,
        /** @var array<WpUser> */
        public readonly array $userDataObjects,
    ) {
    }

    /**
     * @return list<int>
     */
    public function getIDs(): array
    {
        return $this->ids;
    }

    /**
     * @return list<\WP_User>
     */
    public function getUsers(): array
    {
        return acf_get_users([
            'include' => $this->ids,
        ]);
    }

    /**
     * @return array<WpUser>
     */
    public function getUserDataObjects(): array
    {
        return $this->userDataObjects;
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        return new class () implements Cast {
            /**
             * @param DataProperty $property
             * @param mixed        $value
             * @param array<mixed> $context
             *
             * @return WpUsers
             */
            public function cast(DataProperty $property, mixed $value, array $context): WpUsers
            {
                $ids = IDs::castValue($value);
                $users = array_map(fn($id) => new WpUser($id), $ids);
                return new WpUsers($ids, $users);
            }
        };
    }
}
