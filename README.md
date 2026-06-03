# kaiseki/wp-acf-dto

Easily create DTOs from ACF fields.

Maps Advanced Custom Fields (ACF) data onto typed, immutable
[`spatie/laravel-data`](https://github.com/spatie/laravel-data) objects. Define a DTO once, then hydrate
it from a post's ACF fields — with casts that turn raw field values into `WP_Post`/`WP_Term`/`WP_User`
objects, `DateTimeImmutable`s, validated e-mails/URLs, and nested DTOs.

## Installation

```bash
composer require kaiseki/wp-acf-dto
```

Requires PHP 8.2 or newer.

## Usage

### Define a DTO

Extend the package's `Data` base class (a `spatie/laravel-data` data object with a safe-construction
helper) and annotate properties with the bundled casts. Property names map to ACF field names via
spatie's name mappers.

```php
use DateTimeImmutable;
use Kaiseki\WordPress\ACF\Dto\Casts\DateTimeCast;
use Kaiseki\WordPress\ACF\Dto\Casts\WpPostCast;
use Kaiseki\WordPress\ACF\Dto\Castables\Link;
use Kaiseki\WordPress\ACF\Dto\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use WP_Post;

#[MapName(SnakeCaseMapper::class)]
class EventData extends Data
{
    public function __construct(
        public readonly ?string $headline,
        #[WithCast(DateTimeCast::class)]
        public readonly ?DateTimeImmutable $startsAt,
        // Resolves an ACF post-object field to a lazy WP_Post wrapper.
        #[WithCast(WpPostCast::class, postType: 'page')]
        public readonly ?WP_Post $relatedPage,
        // Nested DTO: an ACF link field becomes a Link data object.
        public readonly ?Link $cta,
    ) {
    }
}
```

### Build a DTO from a post's ACF fields

`AcfDataBuilder` reads every field for a post (via ACF's `get_fields()`), merges in any defaults, and
constructs the DTO:

```php
use Kaiseki\WordPress\ACF\Dto\AcfDataBuilder;
use Kaiseki\WordPress\ACF\Dto\AcfGetFields;

$builder = new AcfDataBuilder(new AcfGetFields());

$event = $builder->create(
    EventData::class,
    postId: get_the_ID(),
    defaults: ['headline' => 'Untitled'],
);
```

To swallow construction errors (e.g. validation failures) and get `null` instead of an exception, use
the trait's `safeFrom()`:

```php
$event = EventData::safeFrom(get_fields());
```

### Read single fields with type safety

`AcfFieldValue` wraps `get_field()` with a typed accessor per return type, narrowing ACF's loose return
values to the declared PHP type (or `null`):

```php
use Kaiseki\WordPress\ACF\Dto\AcfFieldValue;

$field = new AcfFieldValue();

$title    = $field->string('headline');           // ?string
$count    = $field->int('seats');                 // ?int
$starts   = $field->dateTime('starts_at');        // ?DateTimeImmutable
$ids      = $field->idList('related_posts');      // list<int>
$author   = $field->wpUser('author');             // ?WpUser
$link     = $field->link('cta');                  // ?Link
```

### Register the spatie/laravel-data config

`ConfigProvider` returns the package's `spatie/laravel-data` configuration (date format, casts,
transformers, normalizers) for laminas-style config aggregators:

```php
use Kaiseki\WordPress\ACF\Dto\ConfigProvider;

$config = (new ConfigProvider())();
```

## Development

```bash
composer install
composer check   # check-deps, cs-check, phpstan
```

## License

MIT — see [LICENSE](LICENSE).
