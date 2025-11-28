# SkillSync - Deployment & Local Development Guide

This guide explains how to run SkillSync locally with Docker and how to prepare it for production deployment.

SkillSync consists of:

- **api/** - Laravel 12 API (PHP 8.3, MySQL, Sanctum)
- **web/** - Vue 3 SPA (Vite)
- **infra/** - Docker Compose stack (Nginx, PHP-FPM, MySQL, phpMyAdmin)

---

## 1. Prerequisites

Install the following before booting the stack:

- Docker Desktop (or Docker Engine)
- Docker Compose
- Node.js 20+
- Git

> You do **not** need PHP or Composer installed locally when using Docker.

---

## 2. Project Structure

```text
/api        → Laravel backend (REST API)
/web        → Vue.js frontend (Vite)
/infra      → Docker environment (nginx + php-fpm + mysql + phpmyadmin)
```

---

## 3. Start the Local Environment

From the project root:

```bash
cd infra
docker compose up -d
```

Services:

- API → http://localhost:8080
- phpMyAdmin → http://localhost:8081
- MySQL → port 3306
- Vue SPA → http://localhost:5173 (when started)

---

## 4. Laravel API Setup

### 4.1 Create the `.env` File

Inside `api/`:

```bash
cd api
cp .env.example .env
```

Then update `.env` with:

```
APP_NAME=SkillSync
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=skillsync
DB_USERNAME=skillsync
DB_PASSWORD=skillsync

SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
```

### 4.2 Generate `APP_KEY`

```bash
docker compose exec php sh -lc "cd /var/www/html && php artisan key:generate"
```

### 4.3 Run Migrations & Seeders

```bash
docker compose exec php sh -lc "cd /var/www/html && php artisan migrate --seed"
```

---

## 5. Running Laravel Commands

Enter the PHP container:

```bash
docker compose exec php sh
cd /var/www/html
```

Examples:

- `php artisan migrate`
- `php artisan tinker`
- `php artisan route:list`
- `php artisan test`

---

## 6. Vue Frontend Setup

Inside `web/`:

```bash
cd web
npm install
```

Start the Vite dev server:

```bash
npm run dev
```

The SPA runs at http://localhost:5173. It talks to the API using the base URL defined in `web/.env`.

---

## 7. Web `.env` Configuration

Inside `web/`:

```bash
cp .env.example .env
```

Minimal required content:

```
VITE_API_BASE_URL=http://localhost:8080
```

This value is referenced in `web/src/api/http.js` as the Axios base URL.

---

## 8. Production Deployment (High-Level)

A typical deployment:

1. Build the SPA:
   ```bash
   cd web
   npm install
   npm run build
   ```
   Outputs static assets to `web/dist`.
2. Serve the built SPA with Nginx or a static hosting provider.
3. Deploy the Laravel API (`api/`) behind Nginx + PHP-FPM with:
   - Managed MySQL database
   - Hosting-provided environment variables
   - `APP_ENV=production`, `APP_DEBUG=false`
4. Run database migrations in production:
   ```bash
   php artisan migrate --force
   ```

Exact hosting (Forge, Vapor, Docker on VPS, etc.) can vary, but the Docker setup in `infra/` is a good starting point.

---

## 9. Troubleshooting

### 9.1 419 / CSRF or Login Issues

Ensure `api/.env` includes:

```
APP_URL=http://localhost:8080
SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
```

Then clear caches:

```bash
docker compose exec php php artisan config:clear
docker compose exec php php artisan cache:clear
docker compose exec php php artisan route:clear
```

### 9.2 Database Not Updating

If migrations or seeders change:

```bash
docker compose exec php sh -lc "cd /var/www/html && php artisan migrate:fresh --seed"
```

---

## 10. Summary

- Docker runs MySQL, PHP-FPM, Nginx, and phpMyAdmin.
- Laravel API is accessible at http://localhost:8080.
- Vue SPA runs via Vite at http://localhost:5173.
- Auth uses Laravel Sanctum with cookie-based sessions.
- Environment configuration lives in `api/.env` and `web/.env`.

SkillSync is now ready for local development and can be extended toward a production deployment.
