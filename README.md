# BPO Control — React + Laravel starter

This bundle contains a connected React frontend and Laravel 13 API backend for the BPO operations SaaS described in the product brief.

## Stack
- React 19 + TypeScript + Vite
- Tailwind CSS v4
- Axios
- Laravel 13
- PostgreSQL
- JWT Auth (`tymon/jwt-auth` 2.3.x)
- Redis-ready queues

## 1. Backend

From `backend/`:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
# configure PostgreSQL credentials in .env
php artisan migrate --seed
php artisan serve
```

Backend: `http://127.0.0.1:8000`

Demo credentials:
- `admin@demo-bpo.test`
- `Password123!`

## 2. Frontend

From `frontend/`:

```bash
npm install
cp .env.example .env
npm run dev
```

Frontend: `http://localhost:5173`

The frontend talks to `VITE_API_URL`.

## 3. Notes

This is a functional foundation, not the final enterprise release. JWT auth, tenant-scoped CRUD, dashboard, workload, SLA monitoring and reporting are wired. The next implementation layer should add granular RBAC/policies, full SLA business calendars, task comments/attachments/dependencies, automation workers/retries, realtime events, export jobs, and integration adapters.
