# Social Network Application

A Laravel-based social network application.

## Installation

Follow these steps to set up the project:

1. **Copy the environment file**
   ```bash
   cp .env.example .env
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Generate application key**
   ```bash
   php artisan key:generate
   ```

4. **Create storage link**
   ```bash
   php artisan storage:link
   ```

5. **Run migrations and seed the database**
   ```bash
   php artisan migrate --seed
   ```

## Login Credentials

After seeding the database, you can log in with the following credentials:

- **Email:** user@gmail.com
- **Username:** user
- **Password:** 123456789

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
