# 📸 Laravel Photo API

**Hobby:** Photography Management App

## Tech Stack

**Backend:** Laravel 12(PHP)

**Frontend:** React+Typescript, Tailwind

#### reference: https://github.com/alfonso-marquez/react-ts-photo-app

## Installation

1. Clone this repo:

```
https://github.com/alfonso-marquez/performativ-photo-api
cd performative-photo-api
```

2. Run composer install

```
composer install
```

3. Copy .env.example → .env and configure your database

4. Run Migrations and Seeder

```
php artisan migrate --seed
```

5. Start the server

```
php artisan serve
```

6. Test API endpoints via Postman or frontend

## CRUD Operations

Create Photo

-   Create, read, update, and soft delete photo metadata (CRUD)
-   Filtering by title, location, camera brand, category
-   Dynamic sorting by any column
-   Validation with custom messages
-   JSON responses for all API endpoints
-   Pagination support

## Filtering & Sorting

Query parameters supported:

-   title → filter by title
-   location → filter by location
-   camera_brand → filter by camera brand
-   photo_category → filter by category
-   photo_taken_from → filter by start date
-   photo_taken_to → filter by end date
-   sort_by → column to sort by (e.g., photo_taken)
-   order → asc or desc

## Third-Party Integration

🐱 Used https://thecatapi.com/ to seed initial photo data, providing placeholder images along with corresponding names for the initial dataset.

## Running Tests

To run tests, run the following command

```bash
  php artisan test
```
