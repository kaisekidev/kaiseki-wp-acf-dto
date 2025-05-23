<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Casts\WpTermsCast;
use Kaiseki\WordPress\ACF\Dto\Exceptions\MissingAttribute;
use Kaiseki\WordPress\ACF\Dto\Util\GetTerms;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use WP_Term;

use function array_pad;
use function count;
use function is_string;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpTerms implements Castable
{
    public function __construct(
        /** @var array<int> */
        private readonly array $ids,
        /** @var string */
        private readonly string $taxonomy,
        /** @var list<WP_Term> */
        private array $terms = [],
        private readonly bool $updateTermMetaCache = false
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
    public function getTerms(): array
    {
        if (count($this->terms) > 0 || count($this->ids) < 1) {
            return $this->terms;
        }

        return $this->terms = GetTerms::getTerms([
            'taxonomy' => $this->taxonomy,
            'include' => $this->ids,
            'update_term_meta_cache' => $this->updateTermMetaCache,
        ]);
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

        return new WpTermsCast($taxonomy);
    }
}
