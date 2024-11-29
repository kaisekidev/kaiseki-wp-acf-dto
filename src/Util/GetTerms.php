<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Util;

use WP_Term;

use function acf_get_terms;
use function function_exists;

class GetTerms
{
    /**
     * @param array<mixed> $args
     *
     * @return list<WP_Term>
     */
    public static function getTerms(array $args): array
    {
        if (!function_exists('acf_get_terms')) {
            return [];
        }

        return acf_get_terms($args);
    }
}
