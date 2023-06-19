<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Kaiseki\WordPress\ACF\Dto\Casts\WpTermCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;

use function count;
use function current;
use function function_exists;
use function is_string;

class WpTermCastable implements Castable
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

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        $taxonomy = is_string($arguments[0]) ? $arguments[0] : 'category';
        return new WpTermCast($taxonomy);
    }
}
