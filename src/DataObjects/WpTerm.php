<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\DataObjects;

use function count;
use function current;
use function function_exists;

class WpTerm
{
    public function __construct(
        private readonly int $id,
        private readonly string $taxonomy = 'category',
    ) {
    }

    public function getTermId(): ?int
    {
        return $this->id;
    }

    public function getTerm(): ?\WP_Term
    {
        if (!function_exists('acf_get_terms')) {
            return null;
        }

        $terms = acf_get_terms(
            [
                'taxonomy'   => $this->taxonomy,
                'include'    => [$this->id],
                'hide_empty' => false,
            ]
        );

        if (count($terms) > 0) {
            return current($terms);
        }

        return null;
    }
}
