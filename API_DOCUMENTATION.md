# API Documentation - Vibrant Tubes

## 1. General Information

This document outlines the available HTTP endpoints for the Vibrant Tubes application.

- **Base URL**: `http://127.0.0.1:8000`
- **Architecture**: Monolithic (Server-Side Rendering)
- **Response Format**: `text/html` (Web Views)
- **Authentication**: Session-based (Cookies)

---

## 2. Public Endpoints

Endpoints accessible to all users (Guest & Authenticated).

### 2.1 Landing Page
Retreives the home page view.

- **URL**: `/`
- **Method**: `GET`
- **Response**: `200 OK` (View: `home.blade.php`)

### 2.2 Product Catalog
Retrieves a paginated list of products with optional filtering and sorting.

- **URL**: `/katalog`
- **Method**: `GET`
- **Query Parameters**:
  | Parameter  | Type   | Description                                      | Example |
  | :---       | :---   | :---                                             | :---    |
  | `q`        | string | Search keyword (matches name or category)        | `shirt` |
  | `category` | string | Filter by category name                          | `Men`   |
  | `page`     | int    | Page number for pagination                       | `2`     |
  | `sort`     | string | Sort order (`default`, `price_asc`, `price_desc`)| `price_asc` |
- **Response**: `200 OK` (View: `katalog.blade.php`)

### 2.3 Product Detail
Retrieves the detail view for a specific product.

- **URL**: `/detail/{slug}`
- **Method**: `GET`
- **URL Parameters**:
  | Parameter | Type   | Description               |
  | :---      | :---   | :---                      |
  | `slug`    | string | Unique slug of the product|
- **Response**: 
  - `200 OK` (View: `detail.blade.php`)
  - `404 Not Found` (If product does not exist)

---

## 3. Authentication

Endpoints for managing user sessions.

### 3.1 Login Page
- **URL**: `/login`
- **Method**: `GET`
- **Response**: `200 OK` (View: `auth.login.blade.php`)

### 3.2 Authenticate User (Submit Login)
- **URL**: `/login`
- **Method**: `POST`
- **Content-Type**: `application/x-www-form-urlencoded`
- **Request Body**:
  | Field      | Type   | Required | Description |
  | :---       | :---   | :---     | :---        |
  | `email`    | email  | Yes      | User email  |
  | `password` | string | Yes      | User password|
- **Response**: 
  - `302 Found` (Redirect to Home/Dashboard on success)
  - `302 Found` (Redirect back to Login on failure)

### 3.3 Register Page
- **URL**: `/register`
- **Method**: `GET`
- **Response**: `200 OK` (View: `auth.register.blade.php`)

### 3.4 Register New User
- **URL**: `/register`
- **Method**: `POST`
- **Content-Type**: `application/x-www-form-urlencoded`
- **Request Body**:
  | Field      | Type   | Required | Description |
  | :---       | :---   | :---     | :---        |
  | `name`     | string | Yes      | Full Name   |
  | `email`    | email  | Yes      | Valid Email |
  | `password` | string | Yes      | Min 8 chars |
  | `password_confirmation` | string | Yes | Must match password |
- **Response**: `302 Found` (Redirect to Home)

### 3.5 Logout
- **URL**: `/logout`
- **Method**: `POST`
- **Response**: `302 Found` (Redirect to Home)

---

## 4. User Features (Authenticated)

Requires standard Session Cookie (`laravel_session`).

### 4.1 Checkout Page
- **URL**: `/checkout`
- **Method**: `GET`
- **Response**: `200 OK` (View: `checkout.index.blade.php`)

### 4.2 Process Checkout
- **URL**: `/checkout/process`
- **Method**: `POST`
- **Request Body**: form-data containing shipping details.
- **Response**: `302 Found` (Redirect to Payment or Success)

### 4.3 Order History
- **URL**: `/riwayat-pesanan`
- **Method**: `GET`
- **Response**: `200 OK` (View: `orders.history.index.blade.php`)

---

## 5. Admin Features (Authenticated + Admin Role)

Prefix: `/admin`

### 5.1 Dashboard
- **URL**: `/admin`
- **Method**: `GET`
- **Response**: `200 OK` (View: `admin.dashboard`)

### 5.2 Product Management
Standard Resource Controller.

| Method | URL | Description |
| :--- | :--- | :--- |
| `GET` | `/admin/products` | List all products |
| `POST` | `/admin/products` | Create new product |
| `GET` | `/admin/products/{id}/edit` | Edit form |
| `PUT` | `/admin/products/{id}` | Update product |
| `DELETE` | `/admin/products/{id}` | Delete product |

### 5.3 Order Management
- **URL**: `/admin/orders`
- **Method**: `GET`
- **Response**: `200 OK` (View: `admin.orders.index`)

### 5.4 Update Order Status
- **URL**: `/admin/orders/{order}/status`
- **Method**: `PUT`
- **Request Body**:
  | Field    | Type   | Description |
  | :---     | :---   | :---        |
  | `status` | string | New status (e.g., `paid`, `shipped`) |
- **Response**: `302 Found` (Redirect back)
