<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Kaiseki\WordPress\ACF\Dto\Casts\WpTermCast;
use Kaiseki\WordPress\ACF\Dto\Exceptions\MissingAttribute;
use Kaiseki\WordPress\ACF\Dto\Util\GetTerms;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use WP_Term;

use function array_pad;
use function count;
use function current;
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

        $terms = GetTerms::getTerms([
            'taxonomy' => $this->taxonomy,
            'include' => [$this->id],
            'hide_empty' => false,
            'update_term_meta_cache' => $this->updateTermMetaCache,
        ]);

        if (count($terms) > 0) {
            return $this->term = current($terms);
        }

        return null;
    }

    public function getTaxonomy(): string
    {
        return $this->taxonomy;
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        [$taxonomy] = array_pad($arguments, 1, null);

        if (
            $taxonomy === null
            || !is_string($taxonomy)
            || $taxonomy === ''
        ) {
            throw MissingAttribute::castableMissingAttribute('taxonomy');
        }

        return new WpTermCast($taxonomy);
    }
}
