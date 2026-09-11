# Laravel Preflight Report

| | |
|---|---|
| **Generated** | 2026-09-10 13:46:19 |
| **Upgrading** | Laravel 11 → 13 |
| **Issues found** | 7 (1 critical, 5 warning, 1 info) |
| **Checks passed** | 28 |

## Issues Requiring Attention

### CODE

#### [WARNING] Collection::groupBy() preserves original keys

Collection::groupBy() now preserves original item keys within each group. Code relying on re-indexed groups (0, 1, 2...) will break.

**Detected in:**
- `app/Livewire/Messenger/History.php`
- `resources/views/livewire/pages/role/permissions.blade.php`

**Fix:** Add ->values() after ->groupBy() where you need zero-indexed groups.

---

#### [WARNING] Broadcasting channel model binding stricter

Broadcasting channel route model binding now enforces that the bound model matches the authenticated user's gate policy. Implicit allows are removed.

**Detected in:**
- `routes/channels.php`

**Fix:** Define explicit channel authorization policies in routes/channels.php for all private/presence channels.

---

#### [WARNING] Container::call() respects nullable class defaults (returns null instead of resolving)

Container::call() with a nullable typed parameter that has a null default now returns null when no binding exists, instead of resolving a class instance.

**Detected in:**
- `app/Repositories/TicketRepository.php`

**Fix:** Review closures/methods injected via Container::call() that use nullable typed parameters with null defaults. Add explicit bindings if you relied on the old resolution behavior.

---

#### [INFO] Collection model serialization now restores eager-loaded relations

When Eloquent model collections are serialized (e.g. in queued jobs), eager-loaded relations are now restored on deserialization.

**Detected in:**
- `app/Events/MessageCreated.php`
- `app/Events/NewTicket.php`
- `app/Events/SeenMessage.php`
- `app/Events/TaskClosed.php`
- `app/Events/TaskCreated.php`
- `app/Events/TaskReferred.php`
- `app/Events/TicketAccepted.php`
- `app/Events/TicketClosed.php`

**Fix:** Review queued jobs that deserialize model collections and depend on relations NOT being present after deserialization.

---

### COMPOSER

#### [WARNING] spatie/laravel-ignition must be upgraded to ^2.0

Laravel 12 requires spatie/laravel-ignition ^2.0. The ^1.x release is incompatible with the updated framework internals.

**Detected in:**
- `composer.json`

**Fix:** Run: composer require spatie/laravel-ignition:^2.0 --dev

---

#### [CRITICAL] Dependency versions bumped

composer.json must be updated: laravel/framework ^13.0, laravel/tinker ^3.0, phpunit/phpunit ^12.0, pestphp/pest ^4.0 (if used).

**Detected in:**
- `composer.json`

**Fix:** Update composer.json: "laravel/framework": "^13.0", "laravel/tinker": "^3.0", "phpunit/phpunit": "^12.0".

---

### CONFIG

#### [WARNING] Cache serializable_classes option defaults to false

The default cache config now includes serializable_classes => false. If your application stores PHP objects in cache, unserialization will fail unless classes are explicitly allow-listed.

**Detected in:**
- `app/Livewire/Cartable/Tickets.php`
- `app/Livewire/Ticket/Index.php`
- `config/cache.php (key "serializable_classes" missing)`

**Fix:** Add a serializable_classes array to config/cache.php listing every class your app serializes into cache. Set to true only if you accept the security risk.

---

## Checks Passed (28)

| Check | Category |
|-------|----------|
| PHP 8.2 minimum required | php |
| doctrine/dbal no longer pulled in automatically | composer |
| Model::reguard() static method removed | code |
| Http Response::json() throws on invalid JSON | code |
| assertJsonPath() strict type comparison | code |
| whereRelation() / orWhereRelation() signature change | code |
| Str::password() removed | code |
| schedule()->withoutOverlapping() default cache store changed | code |
| Storage::fake() returns a new FakeDisk instance | code |
| config/database.php "options" key required for SQLite WAL | config |
| APP_LOCALE and APP_FALLBACK_LOCALE replace config/app.php defaults | env |
| APP_FAKER_LOCALE replaces faker_locale in config/app.php | env |
| VerifyCsrfToken renamed to PreventRequestForgery | middleware |
| DB::upsert() requires non-empty uniqueBy for MySQL/MariaDB | code |
| Cache and session key prefix format changed (hyphen instead of underscore) | env |
| Model booting disallows nested instantiation | code |
| Polymorphic pivot table names are now pluralized | code |
| Domain routes now take precedence over non-domain routes | routes |
| JobAttempted event: $exceptionOccurred replaced by $exception | code |
| QueueBusy event: $connection renamed to $connectionName | code |
| Manager::extend() closures now bound to the manager instance | code |
| MySQL DELETE with JOIN + ORDER BY/LIMIT now produces valid SQL | code |
| Pagination Bootstrap 3 view names changed | code |
| Str factories (UUID/ULID/random) reset between tests | code |
| Js::from() uses JSON_UNESCAPED_UNICODE by default | code |
| symfony/polyfill-php85 — array_first() / array_last() global function conflicts | code |
| Default password reset notification subject changed | code |
| Custom Cache Store contract must implement touch() | code |