<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Cast;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\DataProperty;

use function array_map;
use function is_string;

class WpTerms implements Castable
{
    public function __construct(
        /** @var array<int> */
        private readonly array $ids,
        /** @var array<WpTerm> */
        public readonly array $termDataObjects,
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
     * @return list<\WP_Term>
     */
    public function getTerms(): array
    {
        return acf_get_terms([
            'taxonomy'   => $this->taxonomy,
            'include'    => $this->ids,
        ]);
    }

    /**
     * @return array<WpTerm>
     */
    public function getTermDataObjects(): array
    {
        return $this->termDataObjects;
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        if (!is_string($arguments[0]) || $arguments[0] === '') {
            throw new \InvalidArgumentException('Expected a taxonomy name for WpTerms Castable');
        }
        return new class ($arguments[0]) implements Cast {
            private string $taxonomy;

            public function __construct(string $taxonomy)
            {
                $this->taxonomy = $taxonomy;
            }

            /**
             * @param DataProperty $property
             * @param mixed        $value
             * @param array<mixed> $context
             *
             * @return WpTerms
             */
            public function cast(DataProperty $property, mixed $value, array $context): WpTerms
            {
                $ids = IDs::castValue($value);
                $items = array_map(fn($id) => new WpTerm($id), $ids);
                return new WpTerms($ids, $items, $this->taxonomy);
            }
        };
    }
}
