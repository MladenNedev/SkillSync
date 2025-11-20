# SkillSync – Deployment Plan

This document describes how to run SkillSync using Docker.

---

1. Prerequisites
	•	Docker + Docker Compose installed
	•	Node 20+ and npm (if you want to run Vue outside the container)

2. Clone the repo

3. Configure Laravel .env (dev)

Inside /api :

  cp .env.example .env 

Update important lines to update docker envrionment:

    APP_NAME=SkillSync
    APP_ENV=local
    APP_URL=http://localhost:8080

    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=skillsync
    DB_USERNAME=skillsync
    DB_PASSWORD=skillsync

    FRONTEND_URL=http://localhost:5173
    SANCTUM_STATEFUL_DOMAINS=localhost,localhost:5173
    SESSION_DOMAIN=localhost

4. Start the dev stack with Docker

    From the /infra directory

    -- cd infra
    -- docker compose up -d

This starts:
    - MySQL (db)
    - PHP (Laravel)
    - nginx
    - webdev (Vue dev server)
    - phpMyAdmin

5. Generate app key + migrate database

    docker compose exec php sh
    cd /var/www/html

    php artisan key:generate
    php artisan migrate:fresh --seed

This sets up:
    - App encryption key
    - All tables
    - Seed data (including a demo user)

6. Access the app
    - SPA (dev): http://localhost:5173
    - API via nginx: http://localhost:8080
    - phpMyAdmin: http://localhost:8081 (host depends on compose config)

Demo Login: 
    Email:    demo@skillsync.test
    Password: password

Flow:
1.	Go to http://localhost:5173/login
2.	Login with the demo credentials
3.	The frontend hits:
    - GET /sanctum/csrf-cookie
    - POST /login
    - GET /api/user
4.	After that, authenticated calls to /api/dashboard/* are allowed.

⸻

API Endpoints (Current)

Auth / User
    -   GET /sanctum/csrf-cookie
Initialize CSRF cookies for SPA auth.
    -   POST /login
Session-based login via Sanctum.
    -   POST /logout
(Available via Breeze API stack.)
    -   GET /api/user
Returns the currently authenticated user.
Middleware: auth:sanctum.

----

Dashboard

All require authentication (auth:sanctum):
    -   GET /api/dashboard/summary
Returns cards for:
    -   courses in progress
    -   completed courses
    -   completed challenges
    -   time spent today
    -   GET /api/dashboard/chart
Returns weekly chart data for study sessions (courses vs challenges).
    -   GET /api/dashboard/recent-courses
Returns a list of recently active courses for the user.

