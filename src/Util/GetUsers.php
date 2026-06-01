<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Util;

use WP_User;

use function acf_get_users;
use function array_filter;
use function array_values;
use function function_exists;
use function is_array;

class GetUsers
{
    /**
     * @param array<mixed> $args
     *
     * @return list<WP_User>
     */
    public static function getUsers(array $args): array
    {
        if (!function_exists('acf_get_users')) {
            return [];
        }

        $users = acf_get_users($args);

        return is_array($users)
            ? array_values(array_filter($users, static fn(mixed $user): bool => $user instanceof WP_User))
            : [];
    }
}
