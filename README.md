### Getting Started

It is a basic CRUD generator package for laravel.

If you want to run this project on your local environment, please follow these steps:

#### requirements:
- Laravel package 2. PHP 7.4+
- Laravel 8+

### Install the package:

To discover the package by your composer, you need to udpate your composer by following way

```
...
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/shahadathossen-dev/singularity-crud"
    }
],
...
```
Then run the following comand.

```
composer require singularity/crud
```

next run to publish the vendor styles and layouts 

```
php artisan vendor:publish
```

### Usage:
As per task requirement it will automatically generate a default CRUD for the given model and will run migration to insert the table. Singularity CRUD will generate for you -

- Generate view files
- Generate controller
- Generate model
- Generate validation files
- Generate Migration Files
- Generate routes.
- Run migration for you.

To generate the crud resource of your need, run the command with your model name;

```
php artisan crud:generate ModelName
```

Enjoy your awesome work. Thank you for choosing Singularity CRUD.
