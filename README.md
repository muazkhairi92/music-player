## Music Player[Modular App]


This is the **backend** repository for Music Player[Modular App].

## About Music Player[Modular App]

This app built with in mind for the App to be modular, separated between each module.
The amount of interaction will be very minimal.
It is design for easier debugging based on just the module that is currently been developed.
This was built mainly utilizing [laravel-modules package](https://github.com/nWidart/laravel-modules)


## Technologies

- Laravel 10.x
- PHP ^8
- Composer
- MySql

## Setup

1. Download repository:

```
git clone https://github.com/muazkhairi92/music-player.git
```

2. Installing Dependencies:

```
composer install
```

3. Make 2 Copies of `.env.example` file and rename it to `.env` and `env.testing`

4. Create database based on name chosen in `.env` and `env.testing`

4. To start server:

```
php artisan serve
```
5. To test all test:

```
php artisan test
```


## Development

### Branch

To start new module, create new branch from **staging** branch.

```
git checkout staging
git checkout -b feat/staging-your-new-module
php artisan module:make your-new-module
```

For existing module, create new branch from **staging-module** branch. 

```
git checkout staging/staging-your-new-module
git checkout -b feat/module-
```

Commonly used command to generate necessary files:

```
php artisan module:make-model -m your-model your-module
php artisan module:make-controller your-controller your-module
php artisan module:make-request your-request your-module
php artisan module:make-resource your-resource your-module
php artisan module:make-job your-job your-module
php artisan module:make-test your-test your-module
```

to enable `php artisan test` to run to also your module, 
make change on `phpunit.xml` file to include you module:

```
<directory>Modules/Your-module/tests/Unit</directory>
```


```

### Pull Request

- Push your new module to github
- Create PR and choose merge into **staging** or **staging-module** branch
