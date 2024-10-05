# Project Management Tool Development

This project is a **test project** that includes two complete **CRUD implementations** for **Projects and Tasks** associated with the projects. The backend is built using **Laravel 11**, and the frontend is developed with **HTML**, **CSS(Tailwind css)**, and **JavaScript (jQuery)**.

- [Project Overview](#project-overview)
- [Repository Pattern](#repository-pattern)
    - [How it works](#how-it-works)
- [Testing Feature](#testing-feature)
- [Getting Started](#getting-started)
- [Contributors](#contributors)

## Project Overview

The backend uses the **Repository Pattern** to separate business logic from data access.
For authentication, **two guards (web and api)** have been implemented, as the CRUD operations are structured as **RESTful APIs**.
The project structure covers all **CRUD operations**, including:
Creating, deleting, and listing Projects.
Creating, deleting, editing, and listing Tasks.

## Repository Pattern

This project is built using the **Repository Pattern** to separate business logic from data access. The binding of repositories to their corresponding interfaces is managed automatically within the **RepositoryServiceProvider** file.

### How it works:
The **RepositoryServiceProvider** scans the **App\Repositories** directory and binds each repository class to its interface.
- The **bindRepositoriesToInterfaces** method matches the repositories with their interfaces and registers them in **Laravel’s service container** for easy dependency injection throughout the project.
- This structure ensures **clean architecture** and flexibility for future modifications.

## Testing Feature

A **complete test suite** for the **TaskRepository** has been implemented to validate the repository operations. You can run the tests using the following command:

```php
    php artisan test
```

This command will execute the test cases for various CRUD operations and confirm that the repository behaves as expected.
Note: Keep in mind that running php artisan test **will reset the main database**. Make sure to back up any important data before executing the tests.

## Getting Started

- Clone the repository:
```bash
 git clone https://github.com/mohammaddv/task-management
```

- Navigate to the project directory:
```php
 cd task-management
```

- Install dependencies:
```php
 composer install
```

- Set up environment variables by copying .env.example and set up your database information:
```php
 cp .env.example .env
```

- Generate the application key:
```php
 php artisan key:generate
```

- Run database migrations:
```php
 php artisan migrate
```

- Add the admin user
```php
 php artisan app:add-admin-user
```
Email: admin@gmail.com
Password: admin123

- Start the local server:
```php
 php artisan serve
```



## Contributing
Feel free to submit a pull request or open an issue if you find any bugs or have suggestions for improvement.

## Licence

This project is licensed under the MIT License.
