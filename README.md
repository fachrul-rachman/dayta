## LMP-Dayta – Local Setup

### Requirements

- PHP 8.5+
- Composer
- Node.js and npm
- PostgreSQL

### Installation

1. Install PHP dependencies:

   ```bash
   composer install
   ```

2. Copy the environment file and configure it:

   ```bash
   cp .env.example .env
   ```

   Update the PostgreSQL settings (`DB_*`) and optional AI settings (`AI_SUMMARY_*`).

3. Generate the application key:

   ```bash
   php artisan key:generate
   ```

4. Run migrations and seed demo data:

   ```bash
   php artisan migrate --seed
   ```

5. Install frontend dependencies and build assets:

   ```bash
   npm install
   npm run build
   ```

6. Start the development server:

   ```bash
   php artisan serve
   ```

### Seeded Users

- Admin: `admin@example.com` / `password`
- Director: `director@example.com` / `password`
- HoD (Sales): `hod.sales@example.com` / `password`
- HoD (Operations): `hod.ops@example.com` / `password`

