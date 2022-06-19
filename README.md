## Project setup
```
git clone https://github.com/ManichevaSvetlana/reberry.git
```
```
cd reberry
```
```
composer install
```

## Create .env file and add database credentials
```
cp .env.example .env
```
```
php artisan key:generate
```
```
nano .env
```

## Database setup
```
php artisan migrate
```

Seed users table with a test user:
```
php artisan db:seed --class=UsersTableSeeder
```

Seed countries table:
```
php artisan seed:countries
```

Seed statistics table manually or use Laravel Scheduler [Kernel -> schedule -> hourly command]:
```
php artisan fetch:statistics
```

## Serve the application
```
php artisan octane:start
```




## Testing routes
```
php artisan test
```
