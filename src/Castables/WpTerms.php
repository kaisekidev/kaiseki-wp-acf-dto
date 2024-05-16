<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Casts\WpTermsCast;
use Kaiseki\WordPress\ACF\Exceptions\MissingAttribute;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use WP_Term;

use function acf_get_terms;
use function count;
use function function_exists;
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
        if (count($this->terms) > 0) {
            return $this->terms;
        }

        if (count($this->ids) < 1 || !function_exists('acf_get_terms')) {
            return [];
        }

        return $this->terms = acf_get_terms([
            'taxonomy' => $this->taxonomy,
            'include' => $this->ids,
        ]);
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

        return new WpTermsCast($arguments[0]);
    }
}
