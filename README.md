# PHP_Laravel12_Lasso


## Project Description

PHP_Laravel12_Lasso is a Laravel 12 web application that demonstrates how to integrate the Laravel Lasso package for frontend asset management.

The project includes a simple dark-mode test page to verify that Lasso is properly configured, assets are compiled, published, and pulled correctly. It serves as a starter template for any Laravel project where you want to manage JS/CSS assets efficiently using Lasso.

This project is ideal for Laravel beginners to learn asset management, deployment simulation, and dark-mode UI design.


## Features

- Fully compatible with Laravel 12.

- Dark-mode test page with centered card and neon heading.

- Lasso integration for asset management and deployment simulation.

- Sample JS and CSS assets for testing build and compilation.

- Database-ready setup (optional) for future expansion.

- Clear project folder structure for easy development.

- Git-friendly .gitignore settings to exclude compiled assets.



## Technology Stack

- Backend: Laravel 12 (PHP 8+)

- Frontend: HTML, CSS, JavaScript

- Asset Management: Laravel Lasso + Vite

- Database: MySQL (optional)

- Package Manager: Composer, NPM

- Version Control: Git



---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Lasso "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Lasso

```

#### Explanation:

Installs Laravel 12 and moves into the project folder.




## STEP 2: Database Setup (Optional)

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Lasso
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Lasso

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Connects Laravel to MySQL using your database credentials.

Creates default Laravel tables like users, password_resets, etc., in your database.





## STEP 3: Install Laravel Lasso Package

### Install package:

```
composer require sammyjo20/lasso

```

### Publish config File

```
php artisan vendor:publish --tag=lasso-config

```

### Now config file will be created

```
config/lasso.php

```

### Default configuration example:

```
<?php

declare(strict_types=1);

return [

    'compiler' => [
        'script' => 'npm run build',
        'timeout' => 600,
        'output' => 'progress',
        'excluded_files' => [],
        'excluded_directories' => [],
    ],

    'storage' => [
        'disk' => 'local', // MUST match disk in filesystems.php
        'upload_to' => 'lasso',
        'environment' => env('LASSO_ENV', null),
        'prefix' => env('LASSO_PREFIX', ''),
        'max_bundles' => 5,
    ],

    'webhooks' => [
        'publish' => [],
        'pull' => [],
    ],

    'public_path' => public_path(),
];

```

#### Explanation:

Adds Lasso to manage and deploy frontend assets easily.

Shows default Lasso settings including storage, build commands, and exclusions.






## STEP 4: Setup Filesystem (IMPORTANT)

### Open: config/filesystems.php

#### Ensure:

```
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];


```

### Run:

```
php artisan storage:link

```

#### Explanation:

Ensures Lasso has a correct disk to store/publish assets.

Creates symbolic link so files in storage/app/public are accessible via public/storage.





## STEP 5: Update .gitignore (VERY IMPORTANT)

### Add:

```
/public/build
/public/js
/public/css
.lasso

```

#### Explanation:

Prevents compiled assets from being tracked in Git.





## STEP 6: Create Sample Assets (Vite / JS / CSS)

### Create: resources/js/app.js

```
console.log("Lasso Working 🚀");

```

### Create: resources/css/app.css

```
body {
    background-color: #f5f5f5;
}

```

#### Explanation:

Simple JS file to test asset compilation.

Sample CSS file to test asset compilation.




## STEP 7: Install Node & Build Assets

### Run:

```
npm install
npm run build

```

#### Explanation:

Installs Node dependencies and builds JS/CSS for Lasso to manage.






## STEP 8: Create View

### resources/views/welcome.blade.php

```
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lasso Demo Test Page</title>
    <style>
        /* Full screen dark mode */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #121212;
            /* Dark background */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #ffffff;
        }

        /* Center card */
        .card {
            text-align: center;
            padding: 50px 70px;
            border-radius: 20px;
            background: linear-gradient(135deg, #1f1f1f, #2c2c3c);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #00ffcc;
            /* Neon green heading */
        }

        p {
            font-size: 1.2rem;
            color: #bbbbbb;
        }

        /* Optional hover button */
        .button {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 25px;
            background-color: #00ffcc;
            color: #121212;
            font-weight: bold;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .button:hover {
            background-color: #00d1a8;
            transform: translateY(-3px);
        }
    </style>
</head>

<body>

    <div class="card">
        <h1>Lasso Laravel 12 Project 🚀</h1>
        <p>This is a test page for your Laravel 12 Lasso project</p>

    </div>

</body>

</html>

```

#### Explanation:

Creates a simple dark-mode demo page to verify Lasso and asset setup.





## STEP 9: Publish Assets using Lasso

### Run:

```
php artisan lasso:publish

```

### Expected:


<img src="screenshots/Screenshot 2026-03-18 160903.png" width="900">


#### Explanation:

Publishes your compiled assets to Lasso storage for deployment.





## STEP 10: Pull Assets (Simulate Deployment)

### Run:

```
php artisan lasso:pull

```

### Expected:


<img src="screenshots/Screenshot 2026-03-18 160917.png" width="900">



#### Explanation:

Downloads published assets from storage to public/build for local testing






## STEP 11: Run the App

### Open New Terminal:

```
npm run dev

```


### Start dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000

```

#### Explanation:

Opens the dark-mode test page in your browser.



## Output:


<img src="screenshots/Screenshot 2026-03-18 162634.png" width="900">



---

## Project Folder Structure:

```
PHP_Laravel12_Lasso/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Providers/
├── bootstrap/
│   └── cache/
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── filesystems.php       <-- updated for Lasso storage
│   └── lasso.php             <-- Lasso config
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── node_modules/             <-- after npm install
├── public/
│   ├── build/                <-- Lasso compiled/pulled assets
│   ├── css/                  <-- optional if using css
│   ├── js/                   <-- optional if using js
│   └── index.php
├── resources/
│   ├── css/
│   │   └── app.css            <-- sample CSS
│   ├── js/
│   │   └── app.js             <-- sample JS
│   └── views/
│       └── welcome.blade.php  <-- dark-mode test page
├── routes/
│   └── web.php               <-- default routes
├── storage/
│   ├── app/
│   │   └── public/           <-- Lasso storage for assets
│   └── framework/
│       ├── cache/
│       ├── sessions/
│       └── views/
├── tests/
├── .env                       <-- database & environment config
├── .gitignore                 <-- include /public/build, /public/js, /public/css, .lasso
├── composer.json
├── package.json
├── phpunit.xml
└── vite.config.js             <-- Vite config for assets

```
