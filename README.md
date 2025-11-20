# SkillSync

SkillSync is a full-stack learning tracker focused on web-dev studies.  
Stack: **Laravel API + Vue 3 SPA + Docker + MySQL + Sanctum (session-based auth)**.

Right now the focus is on:
- A clean, realistic architecture (API + SPA + Docker)
- Proper authentication flow using Laravel Sanctum
- A dashboard that summarizes study time, course progress, and challenges

---

## Features (Current Status)

- **Dockerized dev environment**
  - `db` – MySQL 8
  - `php` – Laravel (PHP-FPM)
  - `nginx` – reverse proxy + API gateway
  - `webdev` – Vue 3 dev server (Vite)
  - `phpmyadmin` – DB inspection

- **Authentication**
  - Laravel Breeze API stack
  - Sanctum SPA authentication (stateful, cookie-based)
  - CSRF flow: `/sanctum/csrf-cookie` → `/login` → `/api/user`
  - Protected routes via `auth:sanctum`

- **Domain & Data Model**
  - Users (with `username`)
  - Courses
  - Challenges
  - UserCourseProgress
  - UserChallengeProgress
  - StudySessions
  - All relationships modeled with Eloquent

- **Dashboard Backend**
  - `GET /api/dashboard/summary`
  - `GET /api/dashboard/chart`
  - `GET /api/dashboard/recent-courses`
  - Logic implemented in a dedicated `DashboardService`

- **Frontend API Layer (in progress)**
  - Shared Axios client with CSRF + cookies configured
  - `auth.js` for login + current user
  - `dashboard.js` for dashboard endpoints
  - Login page already authenticates against the real API

---

## Tech Stack

**Backend**
- PHP 8.3
- Laravel (API-only usage)
- MySQL 8
- Laravel Breeze (API)
- Laravel Sanctum

**Frontend**
- Vue 3
- Vite
- Axios

**Infra / Tooling**
- Docker + Docker Compose
- Nginx as reverse proxy
- phpMyAdmin for DB inspection

---

Project Structure (Backend Focus)

api/
├── app/
│   ├── Http/Controllers/        # DashboardController, Auth controllers
│   ├── Models/                  # User, Course, Challenge, Progress, StudySession
│   └── Services/                # DashboardService (aggregation logic)
├── database/
│   ├── migrations/              # All schema definitions
│   └── seeders/                 # Demo data, test users
├── routes/
│   ├── api.php                  # /api/* routes (auth:sanctum + dashboard)
│   └── web.php                  # Not heavily used (SPA handles frontend)
└── ...

Frontend pieces:

web/
├── src/
│   ├── api/
│   │   ├── http.js              # Axios instance (CSRF + cookies)
│   │   ├── auth.js              # login + /api/user wrapper
│   │   └── dashboard.js         # dashboard API wrappers
│   ├── pages/
│   │   ├── Login.vue
│   │   └── Dashboard.vue
│   └── components/
│       └── Nav.vue              # Shared navigation
└── ...

---

Roadmap / Next Steps
    -   Frontend
    -   Finish wiring Dashboard.vue to real dashboard API responses
    -   Add charts (e.g. using Chart.js or similar)
    -   Improve responsive layout & design
    -   Backend
    -   Add more detailed tracking (per-task, per-course stats)
    -   Add endpoints for creating/updating study sessions and progress
    -   DevOps (later)
    -   Basic CI pipeline (GitHub Actions: test + build)
    -   Simple deployment strategy described in DEPLOYMENT.md (planned)