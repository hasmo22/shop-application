## Description

This is a solution for the Watchtowr tech challenge. It satisfies the user stories specified in the requirements, including the bonus section:

1. As a user, I wish to be able to have a list of products to view so that I can purchase them. ✅
2. As a user, I wish to be able to add each product into my cart so that I am able to see what I wish to purchase ✅
3. As a user, I wish to checkout all the items in my cart so that I can order the items. ✅
4. As a user, I wish to see a list of orders so that I know what the status each order. ✅

Bonus:
1. Authenticate the API using Laravel Sanctum. ✅ </br>
The token is stored as a bcrypt hashed value, something like $2y$12$NxA5gDwMO6BgMzhA24Rj9.m4t9PtasEK//zcX0dVQeup7aR7Qp16i </br></br>
The raw token used/sent to the user a base64 version, something like JDJ5JDEyJE54QTVnRHdNTzZCZ016aEEyNFJqOS5tNHQ5UHRhc0VLLy96Y1gwZFZRZXVwN2FSN1FwMTZp

In a production app, you could use something like AWS Cognito to manage Authentication instead of Laravel Sanctum for user pooling, auth providers (Google, etc.).

Excpections:
- Functionality works as expected, there is a frontend which you can use view, add products to a cart and then checkout. 
- Checkout functionality works with a FakePaymentProcessor, an implementation of PaymentProcessorInterface, which would be something like StripePaymentProcessor if implemented for a real application to process payments.
- The checkout would normally have a section to process user's card/payment details but for the purpose of this exercise, this was omitted and out of scope. 
- Code is well organised, follows PSR-4 namespacing. I created controllers, services, models, livewire components and repositories to abstract functionality and follow SOLID principles. These live under the ```app``` directory.
- Database chosen for this exercise is SQLite. 
- API designs are intuitive and easy to follow. You can use ```php artisan route:list``` to view the APIs.

Please follow the instructions specified below to setup the project. This includes:
- Installing dependencies.
- Migrating the database.
- Finally, starting the server and heading over to the frontend.

## Project setup

1. Clone the app
```
git clone git@github.com:hasmo22/laravel-shop.git
cd laravel-shop
```

2. Install dependencies
```
composer install
npm install
npm run build
```

3. Run migrations and seed the database
```
php artisan migrate
php artisan db:seed
```

4. Set the application key
```
cp .env.example .env
php artisan key:generate
```

5. Serve the application.
```
php artisan serve
```

At this point, the application should be running: http://127.0.0.1:800

General comments, frontend considerations and admin panel:
API docs: I didn't have time to generate API docs, I would probably use a library like laravel-apidoc-generator which I've used in the past.
Frontend design: I kept the front-end simple just to illustrate the functionality. If I had time, I'd make it look a little nicer.
Admin panel: Please click and register on screen, this will also log you into the app and you can begin viewing products.

## Run tests

Tests were created for all Controllers and Repositories.

```
php artisan test
```
