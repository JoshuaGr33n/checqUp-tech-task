
# User Management System (DDD-Structured CRUD Application)

This project is a backend technical task demonstrating a CRUD application for managing users. The application follows **Domain-Driven Design (DDD)** principles and implements **Test-Driven Development (TDD)**.

## 🚀 Features

- Full CRUD functionality:
  - Create User
  - Read User Details
  - Update User
  - Delete User
  - View User List
- Image Upload for Profile Picture
- Predefined Country List for Selection
- API Documentation with Swagger (`/api/documentation`)
- Test-Driven Development (TDD)
- Domain-Driven Design (DDD)

---

## 📁 Project Structure

```
app/
├── Application/
│   └── Users/
│       └── Services/
│           ├── FileUploadService.php
│           └── UserService.php
├── Domain/
│   └── Users/
│       ├── Entities/
│       │   └── User.php
│       ├── Factories/
│       │   └── UserFactory.php
│       ├── Repositories/
│       │   └── UserRepositoryInterface.php
│       ├── ValueObjects/
│       │   ├── Email.php
│       │   ├── PhoneNumber.php
│       │   └── UserId.php
│       └── Enums/
│           ├── Country.php
│           └── Gender.php
├── Infrastructure/
│   └── Persistence/
│       └── EloquentUserRepository.php
├── Interfaces/
│   └── Http/
│       └── Controllers/
│           └── Users/
│               └── UserController.php
└── Models/
    └── User.php

tests/
├── Feature/
│   ├── Users/
│   │   ├── CreateUserTest.php
│   │   ├── ListUsersTest.php
│   │   ├── UpdateUserTest.php
│   │   ├── UserDeletionTest.php
│   │   └── ViewUserTest.php
│   └── Support/
│       └── BaseTest.php
└── Unit/
    ├── Application/
    │   └── Users/
    │       └── Services/
    │           ├── FileUploadServiceTest.php
    │           └── UserServiceTest.php
    ├── Domain/
    │   └── Users/
    │       └── Factories/
    │           └── UserFactoryTest.php
    └── Models/
        └── UserModelTest.php
```

---

## 📝 Installation

### Prerequisites

- PHP 8.1+
- Composer
- Laravel 12
- MySQL/SQLite
- Swagger (for API documentation)
- PHPUnit 11.5.15

### Setup Instructions

1. **Clone the Repository**

```bash
git clone https://github.com/JoshuaGr33n/checqUp-tech-task.git
cd checqUp-tech-task
```

2. **Install Dependencies**

```bash
composer install
```

3. **Create Environment File**

```bash
cp .env.example .env
```

4. **Generate Application Key**

```bash
php artisan key:generate
```

5. **Set Up Database**

Update your `.env` file with your database credentials.

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=checqup_tech_task_db
DB_USERNAME=root
DB_PASSWORD=
```

Run Migrations:

```bash
php artisan migrate
```

6. **Run Tests**

```bash
php artisan test
```

---

## 📖 API Documentation

Swagger is implemented for API documentation and can be accessed at:

```
http://localhost:8000/api/documentation
```

---

## 📂 Key Layers

- **Entities:** `app/Domain/Users/Entities/User.php`
- **Repositories:** `app/Domain/Users/Repositories/UserRepositoryInterface.php`
- **Persistence (Eloquent Implementation):** `app/Infrastructure/Persistence/EloquentUserRepository.php`
- **Services:** `app/Application/Services/UserService.php`
- **Controllers:** `app/Interfaces/Http/Controllers/UserController.php`

---

## 📌 Usage

1. **Start the Development Server**

```bash
php artisan serve
```

2. **Access the API Documentation**

```
http://localhost:8000/api/documentation
```

3. **Use API Endpoints for CRUD operations:**

- `POST /api/v1/users` - Create User
- `GET /api/v1/users` - Get All Users
- `GET /api/v1/users/{id}` - Get User Details
- `PUT /api/v1/users/{id}` - Update User
- `DELETE /api/v1/users/{id}` - Delete User

---

## ✅ Testing

Feature and unit tests are located in the `tests/Feature` and `tests/Unit` directories.

Run all tests with:

```bash
php artisan test
```

---

## 📌 Notes

- This project follows **DDD best practices**, with clear separation between Domain, Application, Infrastructure, and Interface layers.
- Swagger is integrated for detailed API documentation.
- TDD is applied with thorough unit and feature tests.

