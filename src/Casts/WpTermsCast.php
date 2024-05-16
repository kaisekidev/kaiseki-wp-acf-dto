<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Castables\WpTerms;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
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
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return WpTerms
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): WpTerms
    {
        return self::castValue($value, $this->taxonomy);
    }

    public static function castValue(mixed $value, string $taxonomy): WpTerms
    {
        if (!is_array($value)) {
            return new WpTerms([], $taxonomy);
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

        return new WpTerms($ids, $taxonomy, $terms);
    }
}
