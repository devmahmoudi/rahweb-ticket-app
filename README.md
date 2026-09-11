# Rahweb Ticket

Rahweb Ticket is a customer support application built with Laravel and Livewire. It provides role-based access for administrators, operators, and customers, with support for tickets, chat, workgroups, media, and permissions.

## Requirements

- PHP 8.4 or newer
- Composer
- Node.js and pnpm (or npm)
- SQLite, or another database supported by Laravel

## Installation

1. Install PHP dependencies:

   ```bash
   composer install
   ```

2. Install frontend dependencies:

   ```bash
   pnpm install
   ```

3. Create the environment file and generate the application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   On Windows PowerShell, use `Copy-Item .env.example .env` instead of `cp`.

4. Configure the database in `.env`. The default configuration uses SQLite. Create the database file if it does not exist:

   ```powershell
   New-Item database/database.sqlite -ItemType File
   ```

   With Bash, use `touch database/database.sqlite`.

5. Run migrations and seed the initial permissions and users:

   ```bash
   php artisan migrate --seed
   ```

## Running the application

Start the Laravel development server:

```bash
php artisan serve
```

In a second terminal, start Vite for frontend assets:

```bash
pnpm dev
```

Open [http://localhost:8000](http://localhost:8000). The root URL redirects to the dashboard after authentication.

The application is configured to use Laravel Reverb for broadcasting. Start it when real-time chat or events are needed:

```bash
php artisan reverb:start
```

For a production frontend build, run `pnpm build`.

## Seeded users

`php artisan migrate --seed` creates the following development accounts. Every account uses the password `123456789`.

| Role | Name | Email | Access |
| --- | --- | --- | --- |
| Administrator | Test Admin | `admin@example.com` | All seeded permissions |
| Operator | Test Operator | `operator@example.com` | Chat, customers, media, messages, tasks, and tickets |
| Customer | Test Customer | `customer@example.com` | Customer account access |

These credentials are for local development only and must be changed or removed before deploying the application.

## Main areas

- Dashboard and profile management
- Role and permission administration
- Workgroups and user assignment
- Operator and customer management
- Ticket creation and closing
- Real-time chat and messaging
- Media uploads

## Useful commands

```bash
php artisan test                  # Run the test suite
php artisan migrate:fresh --seed  # Rebuild the local database and reseed it
php artisan route:list             # List registered routes
vendor/bin/pint                   # Format PHP code
```

## Project structure

- `app/Livewire` - Livewire pages and interactive workflows
- `app/Models` - Eloquent models
- `app/Repositories` - Repository implementations
- `app/Events` and `app/Listeners` - Application events and listeners
- `database/migrations` - Database schema
- `database/seeders` - Permissions, roles, and development data
- `resources/views` - Blade views
- `resources/js` and `resources/css` - Frontend assets
- `routes` - Web, authentication, broadcasting, and console routes
