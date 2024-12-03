<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto;

use function array_merge;

final class AcfDataBuilder
{
    public function __construct(protected readonly AcfGetFields $acfGetFields)
    {
    }

    /**
     * @template T
     *
     * @param class-string<T>      $className
     * @param mixed                $postId
     * @param array<string, mixed> $defaults
     * @param ?array<string>       $excludeFromFormatting
     *
     * @return T
     */
    public function create(
        string $className,
        mixed $postId = false,
        array $defaults = [],
        ?array $excludeFromFormatting = null,
    ) {
        $fieldData = array_merge(
            $defaults,
            $this->acfGetFields->getFields($postId, $excludeFromFormatting) ?? [],
        );

        return $className::from($fieldData);
    }
}
