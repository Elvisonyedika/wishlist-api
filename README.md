# Wishlist API

A RESTful API for managing user wishlists, built with Laravel. This API allows users to register, log in, and manage their wishlists by adding, removing, viewing, and clearing products.

---

## Features

- **Authentication**: User registration, login, and logout.
- **Wishlist Management**:
  - Add products to the wishlist.
  - Remove products from the wishlist.
  - View all wishlist items.
  - Clear the entire wishlist.
- **Product Management**: Fetch all available products.

---

## Installation

### Prerequisites

- PHP >= 8.0
- Composer
- MySQL or any other supported database
- Laravel 10.x

### Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/wishlist-api.git
   cd wishlist-api

2. Install dependencies:
    ```composer install```

3. Set up the .env file:
    ```cp .env.example .env```

Update the .env file with your database and other configurations.

4. Generate the application key:
    ```php artisan key:generate```

5. Run migrations and seed the database:
    ```php artisan migrate --seed```

4. Serve the application:
    ```php artisan serve```

API Documentation
This project includes Swagger documentation for all API endpoints. You can access the Swagger UI to explore and test the API.

Access Swagger Documentation
After starting the application, visit the following URL in your browser:
    ```http://localhost:8000/api/documentation```
    
Replace `localhost:8000` with your application's base URL if it's running on a different host or port.


Running Tests
This project includes feature tests for the AuthController, ProductController, and WishlistController.

To run the tests, use the following command:
    ```php artisan test```


Project Structure
Controllers: Handles API logic (e.g., AuthController, WishlistController, ProductController).
Models: Represents database entities (e.g., User, Wishlist, Product).
Migrations: Defines database schema.
Factories: Generates test data for models.
Tests: Contains feature tests for API endpoints.

License
This project is licensed under the MIT License.

