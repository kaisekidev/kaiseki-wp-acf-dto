<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Kaiseki\WordPress\ACF\Dto\Casts\WpPostsCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;

use function function_exists;

class WpPostsCastable implements Castable
{
    public function __construct(
        /** @var list<int> */
        private readonly array $ids,
        /** @var string|list<string> */
        private readonly string|array $postTypes = '',
    ) {
    }

    /**
     * @return list<int>
     */
    public function getIDs(): array
    {
        return $this->ids;
    }

    /**
     * @return list<\WP_Post>
     */
    public function getPosts(): array
    {
        if (!function_exists('acf_get_posts')) {
            return [];
        }

        return acf_get_posts([
            'post__in'      => $this->ids,
            'post_type'     => $this->postTypes,
            'no_found_rows' => true,
        ]);
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        return new WpPostsCast();
    }
}
