# Queue Ticketing System

This system is responsible for agent serving customer by queuing

The project developed with
- Laravel 11
- PHP 8.2
- MySQL
- Tailwind

# Features

- [x] Agent login admin panel
- [x] Agent create, update, delete and batch create Customers
- [x] Customer get and cancel ticket
- [x] Agent pick, cancel, close ticket
- [ ] Send SMS message to customer using SMS for alert ticket is processing
- [ ] Agent see the tickets and counter overview (serving time, closed rate, ) with dashboard and chart

### Prequisites

- Git
- Composer
- PHP >= 8.2
- MySQL

## Installation

1. Clone the repository

```bash
git clone https://github.com/chung2016/Queue-Ticketing-System.git
```

2. Go to the project directory

```bash
cd Queue-Ticketing-System
```

3. Install the dependencies

```bash
composer install
```

4. Create enviroment file from .env.example file

```bash
cp .env.example .env
```

5. Generate enviroment key and update .env file

```bash
php artisan key:generate
```

6. Migrate database tables with data

```bash
php artisan migrate --seed
```

7. Run the project locally

```bash
php artisan serve
```

8. Navigate http://127.0.0.1 using browser
