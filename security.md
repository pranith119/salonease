SalonEase Security Documentation
==================================


PART 1: THREATS AND MITIGATIONS
---------------------------------


1. THREAT: SQL INJECTION (SQLi)

Description:
An attacker attempts to execute malicious SQL statements through form inputs to manipulate or destroy the database. For example, entering something like '; DROP TABLE users; -- into a name field.

Mitigation:
We exclusively used Laravel's Eloquent ORM for all database interactions. Eloquent uses PDO (PHP Data Objects) parameter binding under the hood, meaning all user input is treated as plain data and never as executable SQL code. We never write raw SQL queries that include user input directly.

Screenshot: [Insert screenshot of CustomerController.php showing Customer::create($validated) line here]


---


2. THREAT: CROSS-SITE SCRIPTING (XSS)

Description:
An attacker stores a malicious JavaScript snippet such as <script>alert('hacked')</script> as a customer name. When another user views the page, the browser executes that script, potentially stealing session cookies or redirecting users to a malicious site.

Mitigation:
All dynamic data is rendered using Laravel Blade's double curly brace syntax {{ }}. This automatically runs PHP's htmlspecialchars() function on every output, converting dangerous characters like < and > into safe HTML entities. The browser displays them as plain text rather than executing them as code.

Screenshot: [Insert screenshot of manage-customers.blade.php showing {{ $customer->full_name }} syntax here]


---


3. THREAT: BROKEN ACCESS CONTROL (PRIVILEGE ESCALATION)

Description:
A staff user directly types /services into their browser URL bar, attempting to bypass the UI and access an admin-only management page to add, edit, or delete services they should not have access to.

Mitigation:
We built a custom middleware class called EnsureUserHasRole. This middleware runs before any request reaches a protected route. It reads the authenticated user's role from the database and compares it against the roles permitted for that route. If the role does not match, a 403 Forbidden response is immediately returned and the page is never rendered.

Screenshot: [Insert screenshot of EnsureUserHasRole.php showing the in_array($user->role, $roles) check here]

Screenshot: [Insert screenshot of web.php showing the /services route with ->middleware('role:admin') here]


---


4. THREAT: BRUTE FORCE / API ABUSE

Description:
An attacker writes a script that sends thousands of login attempts per minute, trying different passwords until they find the correct one. They could also flood the API with requests to crash or slow down the server.

Mitigation:
We configured Laravel's Rate Limiter (throttle:api) on all protected API routes and registered the limiter in AppServiceProvider. This restricts any single user or IP address to a maximum of 60 requests per minute. If the limit is exceeded, the server automatically returns 429 Too Many Requests and blocks further requests until the time window resets.

Screenshot: [Insert screenshot of AppServiceProvider.php showing RateLimiter::for('api') block here]

Screenshot: [Insert screenshot of api.php showing Route::middleware(['auth:sanctum', 'throttle:api']) here]


---


5. THREAT: UNAUTHORIZED API ACCESS (TOKEN SCOPE BYPASS)

Description:
An attacker obtains a valid Sanctum token but it was issued with only read scopes. They attempt to use that token to call a POST or DELETE endpoint to modify or destroy data they should not be able to touch.

Mitigation:
All API tokens are issued with specific Scopes such as customers:read and services:write. Every controller method that performs a write operation explicitly checks whether the token has the correct scope using $request->user()->tokenCan(). If the scope is missing, a 403 Forbidden response is returned immediately and the database is never touched.

Screenshot: [Insert screenshot of ServiceController.php showing the tokenCan('services:write') check here]


---


PART 2: API TEST CASES
-----------------------


TEST CASE 1: Successful Authentication (Happy Path)

Objective: Verify that a valid user receives a Bearer token.

Method: POST
URL: http://localhost:8000/api/login
Body: {"email": "admin@salonease.com", "password": "password"}
Expected Status: 200 OK
Expected Response: JSON containing a token field

Screenshot: [Insert Postman screenshot showing 200 OK and token in response body here]


---


TEST CASE 2: Unauthenticated Access Blocked

Objective: Verify that requests without a token are rejected with 401.

Method: GET
URL: http://localhost:8000/api/customers
Authorization: None
Expected Status: 401 Unauthorized
Expected Response: {"message": "Unauthenticated."}

Screenshot: [Insert Postman screenshot showing 401 Unauthorized response here]


---


TEST CASE 3: Successful Resource Creation

Objective: Verify an authorized admin can create a service via the API.

Method: POST
URL: http://localhost:8000/api/services
Authorization: Bearer Token
Body: {"name": "Haircut", "duration_minutes": 45, "price": 30.00}
Expected Status: 201 Created
Expected Response: JSON object of the newly created service

Screenshot: [Insert Postman screenshot showing 201 Created response here]


---


TEST CASE 4: Validation Failure (Critical Function)

Objective: Verify the API rejects invalid or incomplete data and returns helpful error messages.

Method: POST
URL: http://localhost:8000/api/services
Authorization: Bearer Token
Body: {"name": "Haircut"} (missing required duration_minutes and price fields)
Expected Status: 422 Unprocessable Entity
Expected Response: JSON with errors listing the missing or invalid fields

Screenshot: [Insert Postman screenshot showing 422 response with validation errors here]


---


TEST CASE 5: Token Revocation (Critical Function)

Objective: Verify that a token is completely invalidated after the user logs out.

Step 1 - Login: POST /api/login -> Expected: 200 OK with token
Step 2 - Logout: POST /api/logout with that token -> Expected: 200 OK, "Token revoked successfully"
Step 3 - Retry: GET /api/customers with the same revoked token -> Expected: 401 Unauthorized

Screenshot: [Insert Postman screenshot of the logout 200 OK response here]
Screenshot: [Insert Postman screenshot of the 401 response after using the revoked token here]
