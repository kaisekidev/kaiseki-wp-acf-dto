<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Castables\WpTermsCastable;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use WP_Term;

use function is_array;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpTermsCast implements Cast
{
    public function __construct(
        private readonly string $taxonomy
    ) {
    }

    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return WpTermsCastable
     */
    public function cast(DataProperty $property, mixed $value, array $context): WpTermsCastable
    {
        if (!is_array($value)) {
            return new WpTermsCastable([], $this->taxonomy);
        }

        $ids = [];
        $terms = [];


        foreach ($value as $item) {
            $termId = WpTermCast::getTermId($item);
            if ($termId !== null) {
                $ids[] = $termId;
            }
            if (!($item instanceof WP_Term)) {
                continue;
            }

            $terms[] = $item;
        }

        return new WpTermsCastable($ids, $this->taxonomy, $terms);
    }
}
