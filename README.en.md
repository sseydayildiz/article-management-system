# Article Management System

An article management system built with Laravel 10, featuring user-based authorization and comprehensive feature test coverage.


## Features

- User-scoped article CRUD operations (create, list, update, delete)
- Category-based filtering
- User authentication (auth middleware)
- Ownership-based authorization — users can only view and manage their own articles
- Pagination

## Security: A Vulnerability Found and Fixed During Development

While building this project, an **IDOR (Insecure Direct Object Reference)** vulnerability was discovered in the `edit`, `update`, and `destroy` actions: any authenticated user could manipulate the article ID in the URL to view, update, or delete articles belonging to other users.

**Detection and fix process:**
1. Feature tests were written specifically to prove the vulnerability existed (`test_user_cannot_update_others_article`, `test_user_cannot_delete_others_article`, etc.)
2. The tests initially **failed (red)**, confirming the vulnerability was real
3. An ownership check was added to the controller:
   ```php
   if ($article->user_id !== Auth::id()) {
       abort(403);
   }
   ```
4. The tests were re-run and **passed (green)**

This process demonstrates a practical red-green testing workflow applied to an actual security issue, rather than a purely theoretical example.

## Test Coverage

8 feature tests, 19 assertions — covering authentication, data isolation, and authorization scenarios:

- Unauthenticated users cannot access protected routes
- Users only see their own articles in the listing
- Users cannot open another user's article edit page
- Users cannot update another user's article
- Users cannot delete another user's article
- Article owners can delete their own articles
- Users can create new articles (correctly assigned to the authenticated user)

Tests run against an isolated in-memory SQLite database and never touch the real development database.

```bash
php artisan test --filter=ArticleManagementTest
```

## Tech Stack

- PHP 8.x
- Laravel 10
- Eloquent ORM
- PHPUnit (feature testing)
- SQLite (testing) / MySQL (development)
- Blade

## Setup

```bash
git clone <repo-url>
cd laravel10
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Learning Context

This project was built while learning Laravel from scratch, using a concept-first approach — the HTTP request lifecycle, Eloquent relationships, route model binding, middleware, and feature testing were each studied and understood before being applied in code.
