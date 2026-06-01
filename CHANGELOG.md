# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.0.0 - 2026-06-01

First tagged release.

### Added

- `AcfDataBuilder` — builds a typed DTO from a post's ACF fields (merges defaults, reads via
  `AcfGetFields`).
- `AcfGetFields` — reads ACF field values with control over which field types are (de)formatted.
- `AcfFieldValue` — typed single-field accessors over `get_field()` (`string`, `int`, `float`, `bool`,
  `array`, `dateTime`, `id`, `idList`, `email`, `url`, `link`, `wpPost(s)`, `wpTerm(s)`, `wpUser(s)`).
- `Data` base class with the `WithSafeFrom::safeFrom()` helper, plus casts and castables for ACF →
  `WP_Post`/`WP_Term`/`WP_User`, galleries, links, dates, ids, e-mails, URLs, enums, and numbers.
- `ConfigProvider` exposing the package's `spatie/laravel-data` configuration.

### Changed

- PHP requirement is `^8.2` (PHP 8.4 is the primary target).
- Modernized the dev toolchain (PHPStan 2 at `level: max`, PHPUnit 11 schema,
  composer-require-checker 4); now depends on `kaiseki/php-coding-standard: ^1.0` with the shared
  PHPStan config. CI runs via the reusable workflow in `kaisekidev/.github`.
- Pinned internal dependencies: `kaiseki/config: ^2.0`, `kaiseki/laravel-helper-mocks: ^1.0`
  (were `dev-master`).
- Pinned `illuminate/support` and `illuminate/contracts` to `^11.0 || ^12.0` (resolves to Laravel 12,
  matching `spatie/laravel-data ^4`'s supported range); declared the previously-implicit
  `illuminate/contracts` runtime dependency.
- `respect/validation` stays on `^2.2` — its 3.x line requires PHP 8.5.

### Fixed

- Resolved all PHPStan `level: max` findings at the root (no behaviour change): runtime narrowing of
  ACF's loose `mixed` data before `(int)` casts and before passing defaults to typed cast helpers,
  `list<…>` coercion (`array_values` / typed loops) for post/term/user/id collections, an
  `is_int|is_string` guard in `EnumCast`, and a generic `@implements` on the `Data` contract. Removed
  five pre-existing `@phpstan-ignore` annotations in favour of these root fixes.

### Notes

- The casts in `src/Casts/` carry a single, narrowly path-scoped PHPStan ignore for
  `missingType.generics`: `spatie/laravel-data`'s `Cast::cast()` takes a bare invariant
  `CreationContext`, so no annotation satisfies both `missingType.generics` and the
  contravariance check at once (spatie itself ships a PHPStan baseline for this). The ignore is
  scoped to `src/Casts/*` only — not a global identifier ignore.
