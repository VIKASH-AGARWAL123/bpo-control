# BPO Control Laravel Backend

Laravel 13 API backend for the BPO Control SaaS. Uses PostgreSQL, JWT authentication and Redis/database queues.

## Install

```bash
# First create a fresh Laravel 13 application in this backend directory, or use the provided setup script.
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
php artisan serve
```

Set PostgreSQL and Redis values in `.env` first.

JWT endpoints:
- POST `/api/auth/signup`
- POST `/api/auth/signin`
- POST `/api/auth/logout`
- POST `/api/auth/refresh`
- GET `/api/auth/me`

Protected API resources:
- `/api/dashboard`
- `/api/tasks`
- `/api/clients`
- `/api/processes`
- `/api/teams`
- `/api/queues`
- `/api/sla`
- `/api/workload`
- `/api/automations`
- `/api/reports/summary`

Demo login after seeding:
`admin@demo-bpo.test` / `Password123!`


## Fresh project setup
If this directory came from the starter archive and does not yet contain `artisan` or `public/index.php`, run the Windows setup script from the project root. It creates a clean Laravel 13 project and copies this backend overlay into it.
