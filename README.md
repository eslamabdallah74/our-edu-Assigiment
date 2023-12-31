I've updated the README to include information about the location of the JSON data files. Here's the revised README:

# OurEdu Application

## Overview

OurEdu is an application designed to handle and manage educational data. It provides the ability to insert user and transaction data from JSON files into a database, with separate databases for testing purposes.

## Usage

### Running Tests

To run tests in this application, follow these steps:

1. Ensure that you have configured your database connections for testing in the `.env.testing` file, specifying the `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` environment variables.

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

**Please note that the paths to our JSON data files are located in the `storage/json` directory. You can update these paths to point to different folders or directories as needed.**

### Abstract Class for JSON Data

The application utilizes an abstract class `JsonData` that extends the `JsonDataInterface`. This abstract class contains shared logic and can be extended to create separate services for different data types, such as users and transactions.

### Controller Injection

Controllers within the application use automatic dependency injection. For example, the `UserJsonData` service is automatically injected into the controller, making the code clean and easy to manage.

**Please note that the application retrieves users who have transactions. Users without transactions will not be included in the result.**

---
