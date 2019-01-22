# Laravel API Mix Boilerplate with Authentication JWT

Laravel API Mix is a boilerplate that will provides you a simple and powerful JWT Authentication.

## Installation

To run locally you must install docker-compose, visit [Install Docker Compose](https://docs.docker.com/compose/install/).

**1. Clone repository**

```
git clone https://github.com/mateusgamba/laravel-api-mix.git
```

**2. Enter folder**

```
cd laravel-api-mix
```

**3. Start the application**

```
docker-compose up -d
```

**4. Update dependencies from composer**

```
docker-compose exec app composer update
```

**6. Run Migrations and all database seeds**

```
docker-compose exec app php artisan migrate:refresh --seed
```

Access [http://localhost:9080/api](http://localhost:9080/api).

## Documentation

API documentation [here](https://bit.ly/2CCdqM9).

## Routes

You can install the Postman to execute APIs.

**Authentication**

-   POST: `/auth/register`
-   GET: `/auth/activate/{code}`
-   POST: `/auth/login`
-   GET: `/auth/logout`
-   GET: `/auth/me`

**Password Reset**

-   POST: `/password/forget`
-   GET: `/password/verification/{token}`
-   POST: `/password/reset`

### Docker

To force rebuilding docker containers:

```
docker-compose up -d --force-recreate --build
```
