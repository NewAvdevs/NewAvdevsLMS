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


# NewAvdevsLMS - Mobile-First Learning Management System

A comprehensive Learning Management System built with **Laravel 13**, **NativePHP**, **Tailwind CSS**, and **Livewire** designed for mobile-first education delivery.

## 🚀 Features

- **Course Management** - Create and manage courses, modules, and lessons
- **Quiz System** - Interactive quizzes with native PHP grading engine
- **Progress Tracking** - Real-time progress tracking across all learning content
- **Certificates** - Automatic PDF certificate generation at course completion
- **Mobile-First** - Native iOS/Android apps via NativePHP
- **JWT Authentication** - Secure API authentication for mobile clients
- **Offline Support** - Mobile clients can cache lessons and progress

## 📋 Tech Stack

- **Backend**: Laravel 13 (PHP 8.2+)
- **Mobile**: NativePHP v3+
- **Database**: MySQL (production) / SQLite (mobile)
- **Frontend**: Livewire + Tailwind CSS
- **Authentication**: JWT (tymon/jwt-auth)
- **Certificates**: barryvdh/laravel-dompdf
- **Testing**: Pest PHP (80%+ coverage)

## 🛠 Installation

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 16+

### Setup

```bash
# Clone repository
git clone https://github.com/NewAvdevs/NewAvdevsLMS.git
cd NewAvdevsLMS

# Install dependencies
composer install

# Configure environment
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# Database setup
php artisan migrate

# Start development server
php artisan serve

# Run tests
php artisan test --coverage --min=80
```

## 📱 Mobile Development

```bash
# Initialize NativePHP mobile project
php artisan native:mobile:install

# Run on iOS emulator
php artisan native:mobile:serve ios

# Run on Android emulator
php artisan native:mobile:serve android
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test tests/Unit/Services/ScoreCalculatorTest.php
```

## 📚 API Documentation

### Authentication

**POST** `/api/auth/register`
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123"
}
```

**POST** `/api/auth/login`
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

### Courses

**GET** `/api/courses` - List all courses
**POST** `/api/courses` - Create course
**GET** `/api/courses/{id}` - Get course details
**PUT** `/api/courses/{id}` - Update course
**DELETE** `/api/courses/{id}` - Delete course

### Lessons

**POST** `/api/lessons/{id}/complete` - Mark lesson as complete
```json
{
  "completed_at": "2024-01-01T10:00:00Z"
}
```

### Quizzes

**POST** `/api/quizzes/{id}/submit` - Submit quiz answers
```json
{
  "answers": {
    "1": 0,
    "2": 3,
    "3": 1
  }
}
```

### Progress

**GET** `/api/progress/user` - Get user's overall progress
**GET** `/api/progress/course/{courseId}` - Get course progress
**GET** `/api/progress/module/{moduleId}` - Get module progress

### Certificates

**GET** `/api/certificates` - List user's certificates
**GET** `/api/certificates/{id}/download` - Download certificate PDF

## 📁 Project Structure

```
NewAvdevsLMS/
├── app/
│   ├── Models/
│   ├── Services/
│   ├── Http/Controllers/
│   └── Providers/
├── database/
│   ├── migrations/
│   └── factories/
├── routes/
├── resources/views/
├── tests/
├── config/
└── storage/
```

## 🔐 Security

- JWT tokens expire after 60 minutes
- Refresh tokens valid for 20,160 minutes (14 days)
- All API endpoints require authentication
- CORS properly configured for mobile clients
- Password hashing with bcrypt

## 📝 License

This project is licensed under the MIT License.

## 🤝 Contributing

Contributions are welcome! Please follow our coding standards and include tests.

## 📞 Support

For issues and questions, please create a GitHub issue.
