# SalonEase - Project Documentation

## 1. Project Overview
**SalonEase** is a modern, flexible, and secure server-side web application designed to manage salon operations. The application facilitates the management of customers and services, utilizing a robust role-based access control (RBAC) system to distinguish between administrative and staff capabilities.

This project was developed to fulfill the requirements of the **Server Side Programming - II (COMP50016)** module.

## 2. Technology Stack
*   **Framework:** Laravel 12
*   **Language:** PHP 8.2
*   **Database:** MySQL (via XAMPP)
*   **Frontend UI:** Tailwind CSS, Laravel Blade
*   **Reactive Components:** Laravel Livewire
*   **Authentication & Security:** Laravel Jetstream, Laravel Fortify, Laravel Sanctum (for API)

## 3. Features Implemented

### 3.1 Web Interface
*   **Landing Page:** A premium, responsive, dark-themed promotional page.
*   **Dashboard:** Displays aggregated statistics (Total Customers, Total Services) based on the logged-in user's role.
*   **Customer Management (Livewire):** 
    *   Dynamic search and pagination without page reloads.
    *   Create, Read, Update, and Delete (CRUD) operations via modal interfaces.
*   **Service Catalog (Livewire):**
    *   Dynamic management of salon services (name, duration, price).
    *   Restricted to administrators only.

### 3.2 Role-Based Access Control (RBAC)
*   Users are assigned either an `admin` or `staff` role during registration.
*   A custom middleware (`EnsureUserHasRole`) intercepts requests. 
*   `staff` members can view customers but are completely blocked from accessing or modifying the services catalog.

### 3.3 RESTful API
*   Provides programmatic access to the system.
*   **Authentication:** `POST /api/login` issues scoped Bearer tokens using Laravel Sanctum.
*   **Rate Limiting:** Protects endpoints from brute force and DoS attacks (max 60 requests/minute).
*   **Endpoints:**
    *   `GET /api/customers`
    *   `POST /api/customers`
    *   `GET /api/services` (Cached for 5 minutes to optimize performance)
    *   `POST /api/services`

## 4. API Endpoints Quick Reference

### Authentication
*   `POST /api/login` - Accepts `email` and `password`. Returns a Sanctum token.
*   `POST /api/logout` - Revokes the current token. Requires Bearer Token.

### Customers (Requires Token)
*   `GET /api/customers` - List paginated customers. (Requires `customers:read` scope).
*   `POST /api/customers` - Create a customer. (Requires `customers:write` scope).
*   `PUT /api/customers/{id}` - Update a customer.
*   `DELETE /api/customers/{id}` - Delete a customer.

### Services (Requires Token)
*   `GET /api/services` - List paginated services. (Requires `services:read` scope).
*   `POST /api/services` - Create a service. (Requires `services:write` scope).
*   `PUT /api/services/{id}` - Update a service.
*   `DELETE /api/services/{id}` - Delete a service.

## 5. Security Strategies
*(See `security.md` for the full, detailed audit and threat models required for the assignment).*

In summary, the application prevents:
1.  **SQL Injection** via Eloquent ORM parameterized queries.
2.  **Cross-Site Scripting (XSS)** via Blade template automatic entity escaping.
3.  **Cross-Site Request Forgery (CSRF)** via token verification on all web-based state-changing requests.
4.  **Unauthorized API Access** via Sanctum token scoping and validation.
5.  **Brute Force Attacks** via API and login route rate-limiting.

## 6. How to Run the Application

1.  Start your XAMPP MySQL and Apache servers.
2.  Open your terminal and navigate to the project directory: `cd c:\Users\Pranith\Downloads\salonease\salonease\laravel`
3.  Start the Laravel development server: `php artisan serve`
4.  Open `http://localhost:8000` in your web browser.

## 7. How to Run the Tests

### API Testing via Postman
1.  Import the `SalonEase_API_Collection.json` file into Postman.
2.  Execute the **Login** request first to auto-populate the authorization token.
3.  Run the subsequent Customer and Service requests.

### API Testing via PHP Script
1.  Ensure the Laravel server is running.
2.  Open a new terminal in the `salonease` root directory.
3.  Execute the provided test script: `php api_test_client.php`
