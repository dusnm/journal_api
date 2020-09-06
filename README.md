# Journal API

## A simple API for a journaling application

### Table of Contents
* [Dependency installation](#dependency-installation)
* [Key generation](#key-generation)
* [Database migration](#database-migration)
* [Database seeding](#database-seeding)
* [Logging](#logging)
* [Running the application](#running-the-application)
* [Running tests](#running-tests)
* [Licensing](#licensing)

### Dependency installation
Dependencies are tracked through composer, to install them run:
```
composer install
```

### Key generation
To generate encryption and signature keys, run the commands below:
```
chmod +x key_gen
./key_gen
```

### Database migration
Provided you've filled in the neccessary information in the **.env** file (an example is found in **.env.example**) run the following command:
```
./vendor/bin/phinx migrate
```

### Database seeding
If you wish to seed the database with random data for testing purposes run the following command:
```
./vendor/bin/phinx seed:run -s UserSeeder -s JournalSeeder -s ImageSeeder
```

### Logging
Logging functionality is handled using the **MongoDB** database server. A local or [MongoDB Atlas](https://www.mongodb.com/cloud/atlas) instance is required for normal operation. Supply the connection string in the **.env** file.

### Running the application
Configure your favourite web server to point to the **index.php** file found in the **public** directory, a **.htaccess** file is provided for the **Apache Web Server**. If you wish to run the built in php development server for testing purposes run:
```
php -S <IP_ADDRESS>:<TCP_PORT> -t public/
```

### Running tests
To run unit tests, run the following command:
```
./vendor/bin/phpunit
```

### Licensing
Journal API is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

Journal API is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Journal API. If not, see [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0).
