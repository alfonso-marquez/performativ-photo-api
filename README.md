# Photo API

A **RESTful API** built with **Laravel 12** for managing photo metadata. Designed for use with frontend applications to **view, filter, add, update, and sort photos**.

---

## Features

-   Create, read, update, and soft delete photo metadata (CRUD)
-   Filtering by title, location, camera brand, category, and date range
-   Dynamic sorting by any column
-   Validation with custom messages
-   JSON responses for all API endpoints
-   Pagination support

---

## Technology Stack

-   **Backend:** Laravel 12, PHP 8.4+
-   **Database:** MySQL
-   **API:** RESTful JSON
-   **Frontend:** React, Typescript

---

## API Endpoints

| GET | `/api/photos` | List all photos (paginated)  
| GET | `/api/photos/{id}` | Show a single photo  
| POST | `/api/photos` | Create a new photo  
| PUT | `/api/photos/{id}` | Update photo metadata  
| DELETE | `/api/photos/{id}` | Soft delete a photo

---

## Filtering & Sorting

**Query parameters supported:**

-   `title` → filter by title
-   `location` → filter by location
-   `camera_brand` → filter by camera brand
-   `photo_category` → filter by category
-   `photo_taken_from` → filter by start date
-   `photo_taken_to` → filter by end date
-   `sort_by` → column to sort by (e.g., `photo_taken`)
-   `order` → `asc` or `desc`

**Example:**

```json
{
    "title": "Sunset at Beach",
    "description": "A beautiful sunset photo",
    "location": "Malibu",
    "photo_category": "Landscape",
    "camera_brand": "Canon",
    "gear_used": "50mm f/1.8 lens, no flash",
    "photo_taken": "2025-09-10",
    "photo_path": "/uploads/photo1.jpg"
}
```

## Setup Instructions

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` → `.env` and configure your database
4. Run migrations: `php artisan migrate`
5. Start the server: `php artisan serve`
6. Test API endpoints via Postman or frontend

---
