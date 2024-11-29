<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Util;

use WP_User;

use function acf_get_users;
use function function_exists;

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

        return acf_get_users($args);
    }
}
