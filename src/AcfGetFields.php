<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto;

use WP_Post;

use function add_filter;
use function function_exists;
use function get_fields;
use function get_the_ID;
use function in_array;
use function is_array;
use function is_int;
use function remove_filter;

class AcfGetFields
{
    /**
     * @param mixed         $postId
     * @param array<string> $excludeFromFormatting
     *
     * @return array<string, mixed>|null
     */
    public function getFields(
        mixed $postId = false,
        ?array $excludeFromFormatting = null,
    ): ?array {
        if (!function_exists('get_fields')) {
            return null;
        }
        $excludeFromFormatting = $excludeFromFormatting === null
            ? $this->getFieldsExcludedFromFormatting()
            : $excludeFromFormatting;
        $filter = function (
            mixed $check,
            mixed $value,
            mixed $postId,
            array $field,
        ) use ($excludeFromFormatting): mixed {
            $type = $field['type'] ?? '';
            if (in_array($type, $excludeFromFormatting, true)) {
                return $value;
            }

            return null;
        };
        add_filter('acf/pre_format_value', $filter, 10, 4);
        add_filter('acf/format_value', [$this, 'normalizeEmptyFieldValues'], 10, 3);
        $values = get_fields($this->getPostId($postId));
        remove_filter('acf/pre_format_value', $filter);
        remove_filter('acf/format_value', [$this, 'normalizeEmptyFieldValues']);

        return is_array($values) ? $values : [];
    }

    /**
     * @param mixed         $postId
     * @param array<string> $format
     *
     * @return array<string, mixed>|null
     */
    public function getRawFields(
        mixed $postId = false,
        ?array $format = null,
    ): ?array {
        if (!function_exists('get_fields')) {
            return null;
        }
        $format = $format === null
            ? $this->getRawFieldsIncludedInFormatting()
            : $format;
        $filter = function (
            mixed $check,
            mixed $value,
            mixed $postId,
            array $field,
        ) use ($format): mixed {
            $type = $field['type'] ?? '';
            if (in_array($type, $format, true)) {
                return null;
            }

            return $value;
        };
        add_filter('acf/pre_format_value', $filter, 10, 4);
        add_filter('acf/format_value', [$this, 'normalizeEmptyFieldValues'], 10, 3);
        $values = get_fields($this->getPostId($postId));
        remove_filter('acf/pre_format_value', $filter);
        remove_filter('acf/format_value', [$this, 'normalizeEmptyFieldValues']);

        return is_array($values) ? $values : [];
    }

    /**
     * @return list<string>
     */
    public function getFieldsExcludedFromFormatting(): array
    {
        return [
            'time_picker',
            'date_picker',
            'date_time_picker',
        ];
    }

    /**
     * @return list<string>
     */
    public function getRawFieldsIncludedInFormatting(): array
    {
        return [
            'repeater',
            'group',
            'clone',
            'select',
            'checkbox',
            'radio',
            'true_false',
            'button_group',
        ];
    }

    /**
     * @param mixed                $value
     * @param mixed                $postId
     * @param array<string, mixed> $field
     *
     * @return mixed
     */
    public function normalizeEmptyFieldValues(mixed $value, mixed $postId, array $field): mixed
    {
        return isset($field['type']) && $field['type'] !== 'true_false' && $value === false
            ? null
            : $value;
    }

    private function getPostId(mixed $postId): int|false
    {
        if ($postId === false) {
            return false;
        }
        if (is_int($postId)) {
            return $postId;
        }
        if ($postId instanceof WP_Post) {
            return $postId->ID;
        }

        return get_the_ID();
    }
}
