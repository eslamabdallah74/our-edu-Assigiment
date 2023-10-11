# OurEdu Application

## Overview

OurEdu is an application designed to handle and manage educational data. It provides the ability to insert user and transaction data from JSON files into a database, with separate databases for testing purposes.

## Usage

### Running Tests

To run tests in this application, follow these steps:

1. Ensure that you have configured your database connections for testing in the `.env` file, specifying the `DB_DATABASE_TEST`, `DB_USERNAME_TEST`, and `DB_PASSWORD_TEST` environment variables.

2. When running tests, specify the desired connection using the `--env` flag. For example, to run tests using the `testing` connection:

   ```sh
   php artisan test --env=testing
   ```

This ensures that the tests use the correct database configuration.

### JSON Data Insertion

The application is structured to handle JSON data insertion. It uses a common abstract class `JsonData` that implements the `JsonDataInterface`. This class contains most of the shared logic.

### Configuration Paths

The application's configuration file contains paths to JSON files. These paths can be customized to point to different folders or locations. The configuration file can be found at `config/paths.php`. Here is an example of how it's set up:

```php
return [
    'user_json_path' => storage_path('json/users.json'),
    'transaction_json_path' => storage_path('json/transactions.json'),
];
```

You can update these paths to match your specific needs or to point to new folders or directories.

### Abstract Class for JSON Data

The application utilizes an abstract class `JsonData` that extends the `JsonDataInterface`. This abstract class contains shared logic and can be extended to create separate services for different data types, such as users and transactions.

### Controller Injection

Controllers within the application use automatic dependency injection. For example, the `UserJsonData` service is automatically injected into the controller, making the code clean and easy to manage.

---

Feel free to expand on this base description and add further details as needed for your specific application.
