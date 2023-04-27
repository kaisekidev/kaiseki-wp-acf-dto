<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Object;

use WP_Term;

use function count;
use function function_exists;

class WpTerms
{
    public function __construct(
        /** @var array<int> */
        private readonly array $ids,
        /** @var string */
        private readonly string $taxonomy,
    ) {
    }

    /**
     * @return array<int>
     */
    public function getIDs(): array
    {
        return $this->ids;
    }

    /**
     * @return list<WP_Term>
     */
    public function getTerm(): array
    {
        if (count($this->ids) < 1 || !function_exists('acf_get_terms')) {
            return [];
        }

        return acf_get_terms([
            'taxonomy'   => $this->taxonomy,
            'include'    => $this->ids,
        ]);
    }
}
