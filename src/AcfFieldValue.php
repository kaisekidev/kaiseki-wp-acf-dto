<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto;

use DateTimeImmutable;
use Kaiseki\WordPress\ACF\Dto\Castables\Link;
use Kaiseki\WordPress\ACF\Dto\Castables\WpPost;
use Kaiseki\WordPress\ACF\Dto\Castables\WpPosts;
use Kaiseki\WordPress\ACF\Dto\Castables\WpTerm;
use Kaiseki\WordPress\ACF\Dto\Castables\WpTerms;
use Kaiseki\WordPress\ACF\Dto\Castables\WpUser;
use Kaiseki\WordPress\ACF\Dto\Castables\WpUsers;
use Kaiseki\WordPress\ACF\Dto\Casts\DateTimeCast;
use Kaiseki\WordPress\ACF\Dto\Casts\EmailCast;
use Kaiseki\WordPress\ACF\Dto\Casts\IdCast;
use Kaiseki\WordPress\ACF\Dto\Casts\IdsCast;
use Kaiseki\WordPress\ACF\Dto\Casts\LinkCast;
use Kaiseki\WordPress\ACF\Dto\Casts\UrlCast;
use Kaiseki\WordPress\ACF\Dto\Casts\WpPostCast;
use Kaiseki\WordPress\ACF\Dto\Casts\WpPostsCast;
use Kaiseki\WordPress\ACF\Dto\Casts\WpTermCast;
use Kaiseki\WordPress\ACF\Dto\Casts\WpTermsCast;
use Kaiseki\WordPress\ACF\Dto\Casts\WpUserCast;
use Kaiseki\WordPress\ACF\Dto\Casts\WpUsersCast;

use function get_field;
use function is_array;
use function is_numeric;
use function is_string;

class AcfFieldValue
{
    public function string(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?string {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return is_string($value) ? $value : null;
    }

    public function int(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?int {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return is_numeric($value) ? (int)$value : null;
    }

    public function float(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?float {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return is_numeric($value) ? (float)$value : null;
    }

    public function bool(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): bool {
        return (bool)get_field($selector, $postId, $formatValue, $escapeHtml);
    }

    /**
     * @param string  $selector
     * @param ?string $postId
     * @param bool    $formatValue
     * @param bool    $escapeHtml
     *
     * @return array<mixed>|null
     */
    public function array(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?array {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return is_array($value) ? $value : null;
    }

    public function dateTime(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?DateTimeImmutable {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return DateTimeCast::castValue($value);
    }

    public function id(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?int {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return IdCast::castValue($value);
    }

    /**
     * @param string  $selector
     * @param ?string $postId
     * @param bool    $formatValue
     * @param bool    $escapeHtml
     *
     * @return list<int>
     */
    public function idList(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): array {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return IdsCast::castValue($value);
    }

    public function email(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?string {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return EmailCast::castValue($value);
    }

    public function url(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?string {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return UrlCast::castValue($value);
    }

    public function link(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?Link {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return LinkCast::castValue($value);
    }

    public function wpPost(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?WpPost {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return WpPostCast::castValue($value);
    }

    public function wpPosts(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?WpPosts {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return WpPostsCast::castValue($value);
    }

    public function wpTerm(
        string $selector,
        string $taxonomy,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?WpTerm {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return WpTermCast::castValue($value, $taxonomy);
    }

    public function wpTerms(
        string $selector,
        string $taxonomy,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?WpTerms {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return WpTermsCast::castValue($value, $taxonomy);
    }

    public function wpUser(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?WpUser {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return WpUserCast::castValue($value);
    }

    public function wpUsers(
        string $selector,
        ?string $postId = null,
        bool $formatValue = true,
        bool $escapeHtml = false
    ): ?WpUsers {
        $value = get_field($selector, $postId, $formatValue, $escapeHtml);

        return WpUsersCast::castValue($value);
    }
}
