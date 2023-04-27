<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Cast;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Object\WpTerms;
use Spatie\LaravelData\Attributes\GetsCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function array_reduce;
use function is_array;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WithWpTerms implements GetsCast
{
    public function __construct(
        private readonly string $taxonomy
    ) {
    }

    public function get(): Cast
    {
        return new class ($this->taxonomy) implements Cast {
            public function __construct(
                private readonly string $taxonomy
            ) {
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
                if (!is_array($value)) {
                    return new WpTerms([], $this->taxonomy);
                }
                $ids = array_reduce($value, function ($carry, $item) {
                    $postId = WithWpTerm::getTermId($item);
                    if ($postId !== null) {
                        $carry[] = $postId;
                    }
                    return $carry;
                }, []);
                return new WpTerms($ids, $this->taxonomy);
            }
        };
    }
}
