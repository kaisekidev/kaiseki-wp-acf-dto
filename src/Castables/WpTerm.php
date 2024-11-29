<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Kaiseki\WordPress\ACF\Dto\Casts\WpTermCast;
use Kaiseki\WordPress\ACF\Exceptions\MissingAttribute;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use WP_Term;

use function acf_get_terms;
use function count;
use function current;
use function function_exists;
use function is_string;

class WpTerm implements Castable
{
    public function __construct(
        private readonly int $id,
        private readonly string $taxonomy = 'category',
        private ?WP_Term $term = null,
        private readonly bool $updateTermMetaCache = false
    ) {
    }

    public function getTermId(): ?int
    {
        return $this->id;
    }

    public function getTerm(): ?WP_Term
    {
        if ($this->term !== null) {
            return $this->term;
        }

        if (!function_exists('acf_get_terms')) {
            return null;
        }

        $terms = acf_get_terms(
            [
                'taxonomy' => $this->taxonomy,
                'include' => [$this->id],
                'hide_empty' => false,
                'update_term_meta_cache' => $this->updateTermMetaCache,
            ]
        );

        if (count($terms) > 0) {
            return $this->term = current($terms);
        }

        return null;
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        if (!isset($arguments[0]) || !is_string($arguments[0])) {
            throw MissingAttribute::castableMissingAttribute('taxonomy');
        }

        return new WpTermCast($arguments[0]);
    }
}
