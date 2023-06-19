<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Castables\WpUsersCastable;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function array_reduce;
use function is_array;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpUsersCast implements Cast
{
    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return WpUsersCastable
     */
    public function cast(DataProperty $property, mixed $value, array $context): WpUsersCastable
    {
        if (!is_array($value)) {
            return new WpUsersCastable([]);
        }
        $ids = array_reduce($value, function ($carry, $item) {
            $postId = WpUserCast::getUserId($item);
            if ($postId !== null) {
                $carry[] = $postId;
            }
            return $carry;
        }, []);
        return new WpUsersCastable($ids);
    }
}
