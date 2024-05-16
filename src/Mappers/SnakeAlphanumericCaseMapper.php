<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Mappers;

use Illuminate\Support\Str;
use Spatie\LaravelData\Mappers\NameMapper;

use function is_string;
use function preg_match;
use function preg_replace;

class SnakeAlphanumericCaseMapper implements NameMapper
{
    /**
     * The cache of snake-cased words.
     *
     * @var array<string,string>
     */
    protected static array $cache = [];

    public function map(int|string $name): string|int
    {
        if (!is_string($name)) {
            return $name;
        }

        return self::convert(Str::snake($name));
    }

    /**
     * Convert a string to snake case.
     *
     * @param string $value
     *
     * @return string
     */
    private static function convert(string $value): string
    {
        $key = $value;

        if (isset(static::$cache[$key])) {
            return static::$cache[$key];
        }

        if ((bool)preg_match('~[0-9]+~', $value)) {
            $value = (string)preg_replace('/(?<=[a-zA-Z])(\d)/u', '_$1', $value);
        }

        return static::$cache[$key] = $value;
    }
}
