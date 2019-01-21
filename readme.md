# Laravel API Mix Boilerplate with Authentication JWT

Laravel Boilerplate provides you simple and powerful JWT Authentication.

## Installation

To run locally you must install docker-compose, visit [Install Docker Compose](https://docs.docker.com/compose/install/).

**1. Clone repository**

```
git clone https://github.com/mateusgamba/laravel-api-mix.git
```

**2. Enter folder**

```
cd laravel-api-jwt
```

**3. Start the application**

```
docker-compose up -d
```

**4. Run Migrations and all database seeds**

```
docker-compose exec app php artisan migrate:refresh --seed
```

Access [http://localhost:9000/api](http://localhost:9000/api).

## Documentation

API documentation [here](https://bit.ly/2CCdqM9).

## Routes

You can install the Postman to execute APIs.

**Authentication**

-   POST: `/auth/login`
-   GET: `/auth/logout`
-   POST: `/auth/register`
-   GET: `/auth/activate/{code}`
-   GET: `/auth/user`

**Password Reset**

-   POST: `/password/forget`
-   GET: `/password/verification/{token}`
-   POST: `/password/reset`
