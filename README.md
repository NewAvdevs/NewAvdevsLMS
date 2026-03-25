# NewAvdevsLMS

## Project Overview
Mobile-First Learning Management System built with Laravel 13 and NativePHP.

🚀 Installation Instructions
Clone the repository:

git clone https://github.com/NewAvdevs/NewAvdevsLMS.git
cd NewAvdevsLMS

Install dependencies:

composer install

Create environment file:

cp .env.example .env

Generate keys:

php artisan key:generate
php artisan jwt:secret

Run migrations:

php artisan migrate

Start server:

php artisan serve

Run tests:

php artisan test --coverage --min=80
