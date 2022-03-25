# Bank Demo Laravel API
An Internal API for a fake financial Institution

## What's this repo about

An API that perform basic bank operations and can be consuemd by multiple frontends(Web, iOS, Android etc)

# Laravel Bank Demo

A simple bank demo App with Laravel 8.7.

## Installation

Clone the repository-
```
git clone https://github.com/Enigmatec/bank-demo.git
```

Then cd into the folder with this command-
```
cd bank-demo
```

Then do a composer install
```
composer install
```

Then create a environment file using this command-
```
cp .env.example .env
```

Then edit `.env` file with appropriate credential for your database server. Just edit these two parameter(`DB_USERNAME`, `DB_PASSWORD`).

Then create a database named `bank-demo` and then do a database migration using this command-
```
php artisan migrate
```

## Run server

Run server using this command-
```
php artisan serve
```
## Post API Documentation Link
https://documenter.getpostman.com/view/16200299/UVyn1JRN







