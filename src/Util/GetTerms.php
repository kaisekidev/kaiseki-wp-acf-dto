<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Util;

use WP_Term;

use function acf_get_terms;
use function array_filter;
use function array_values;
use function function_exists;
use function is_array;

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

        $terms = acf_get_terms($args);

        return is_array($terms)
            ? array_values(array_filter($terms, static fn(mixed $term): bool => $term instanceof WP_Term))
            : [];
    }
}
