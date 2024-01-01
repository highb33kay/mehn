# Mehn - Bank Statement Laravel Application

This is a Laravel application for handling bank statements, including CSV file uploads and associated transactions.

## Getting Started

### Prerequisites

-   PHP (>= 7.3)
-   Composer
-   MySQL (or any other database supported by Laravel)

## Technologies and Libraries

-   **Laravel Sanctum:** For API token authentication.

## Compatibility

-   **Supported File Formats:**
    -   csv
-   **Browser Compatibility:**
    -   Chrome, Firefox, Safari, Edge.
-   **Device Compatibility:**
    -   Desktop and mobile devices.

## Getting Started

1. Clone the repository.
2. Install dependencies: `composer install`.
3. Set up your environment variables.
4. Run migrations: `php artisan migrate`.
5. Start the development server: `php artisan serve`.

## Create DB

`CREATE DATABASE "your_db_name";`

Ensure that you end the command with a semicolon and try running it again. After that, you can proceed with creating a user and granting privileges as needed. For example:

`CREATE USER youruser WITH ENCRYPTED PASSWORD 'yourpassword';`

`ALTER ROLE youruser SET client_encoding TO 'utf8';`

`ALTER ROLE youruser SET default_transaction_isolation TO 'read committed';`

`ALTER ROLE youruser SET timezone TO 'UTC';`

`GRANT ALL PRIVILEGES ON DATABASE your_db_name TO youruser;`

## Contributors

-   [Ibukun Alesinloye](https://highb33kay.me)

## License

This project is licensed under the [License Name] License - see the [LICENSE](LICENSE) file for details.
