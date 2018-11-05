# CAPP Co-Assessment of Presentations by Peers

## Prerequisites
1. Requirements from a Laravel 5.5 Project
2. MariaDB database with 'utf8mb4' encoding
3. NodeJS v10 or above
4. Supervisor For Database Queue

## Checklist of deployment
1. Update Database config in `.env` file
2. Composer Install dependencies - `composer install`
3. Install NodeJS dependencies - `npm i`
4. Build production JS / CSS for the project - `npm run prod`
5. Run Database Migration - `php artisan migrate`
6. Run Encryption Key Generation - `php artisan key:generate`
7. Run Data Seeding - `php artisan db:seed`
8. Setup queue with supervisor or use `php artisan queue:work` to start queue workers for email sending
9. Setup Nginx / Apache (HTTPS recommended)