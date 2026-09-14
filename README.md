# Rahweb Ticket

Rahweb Ticket is a customer support application built with Laravel and Livewire. It provides role-based access for administrators, operators, and customers, with support for tickets, chat, workgroups, media, and permissions.

## Requirements

- PHP 8.4 or newer
- Composer
- Node.js and pnpm (or npm)
- PostgreSQL or MySQL is recommended for this project; SQLite is only suitable for very light local testing

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

4. Configure the database in `.env`.

   > Note: SQLite is not recommended for this project. It can struggle with concurrent queries and may throw `database is locked` errors in normal use. For a more reliable setup, prefer PostgreSQL or MySQL.

   Example `.env` configuration for PostgreSQL:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=rahweb_ticket
   DB_USERNAME=postgres
   DB_PASSWORD=your_password
   ```

   Example `.env` configuration for MySQL:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=rahweb_ticket
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

   If you still use SQLite for a quick local test, create the database file first:

   ```powershell
   New-Item database/database.sqlite -ItemType File
   ```

   With Bash, use `touch database/database.sqlite`.

5. Run migrations and seed the initial permissions and users:

   ```bash
   php artisan migrate --seed
   ```

## Running the application

After the installation steps above are complete, start the services you need in separate terminals.

```bash
php artisan serve
pnpm dev
php artisan reverb:start
php artisan queue:work
php artisan queue:work --queue=webservice
php artisan schedule:work
```

Open [http://localhost:8000](http://localhost:8000). The root URL redirects to the dashboard after authentication.

For a production frontend build, run `pnpm build`.

## Architecture and feature documentation

- [Send Ticket to Webservice](docs/send-ticket-to-webservice.md)
- [Static RBAC](docs/static-rbac.md)
- [Ticket State Management](docs/ticket-state-management.md)
- [User Notifications](docs/user-notifications.md)

## Seeded users

`php artisan migrate --seed` creates the following development accounts. Every account uses the password `123456789`.

| Role | Name | Email | Notes |
| --- | --- | --- | --- |
| Superadmin | Test Superadmin | `superadmin@example.com` | Created by `DatabaseSeeder::seedSuperadminUser()` |
| Operator | Test Operator | `operator@example.com` | Created by `DatabaseSeeder::seedOperatorUser()` and attached to the seeded workgroup `پشتیبانی` |
| Customer | Test Customer | `customer@example.com` | Created by `DatabaseSeeder::seedCustomerUser()` |

These credentials are for local development only and must be changed or removed before deploying the application.

## Main areas

- Dashboard and profile management
- Workgroups and user assignment
- Real-time chat and messaging
- Tickets
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
