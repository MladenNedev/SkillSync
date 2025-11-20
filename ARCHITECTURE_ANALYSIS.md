// Folder Structure //

Project 3 - SkillSync/
│
├── api/                          # Laravel Backend (REST API)
│   ├── app/                      # Core Laravel application code
│   │   ├── Http/Controllers      # API controllers
│   │   ├── Models                # Eloquent models
│   │   └── Providers             # Service providers
│   │
│   ├── bootstrap/                # App bootstrapping
│   │   └── app.php               # Registers the routing files
│   │
│   ├── config/                   # Application configuration
│   ├── database/                 # Migrations & seeders
│   ├── public/                   # Public web root (served by nginx)
│   │   └── index.php             # Laravel entry point
│   ├── routes/                   # Route definitions
│   │   ├── api.php               # API routes (/api/*)
│   │   ├── web.php               # Web routes (not used heavily for SPA)
│   └── vendor/                   # Composer packages
│
├── web/                          # Vue Frontend (Vite-powered SPA)
│   ├── src/
│   │   ├── App.vue               # Root Vue component
│   │   ├── main.js               # Vue application entry point
│   │   ├── components/           # Reusable UI components
│   │   └── style.css             # Global styling
│   ├── public/                   # Static assets
│   ├── package.json              # JS dependencies
│   └── vite.config.js            # Vite config + dev server settings
│
└── infra/                        # Docker infrastructure layer
    ├── compose.yml               # Definitions for all running services
    └── docker/
        ├── nginx/                # Web server (reverse proxy)
        │   ├── Dockerfile
        │   └── default.conf      # Routing rules for API ↔ Vue
        ├── php-fpm/              # PHP runtime image
        │   └── Dockerfile
        └── node/                 # Node environment image
            └── Dockerfile

// How Everything Connects To Eachother //

Docker Services (from infra/compose.yml)

1. db — MySQL
	•	Stores all application data.
	•	Exposed on port 3307.

2. php — Laravel API (PHP-FPM)
	•	Runs the backend.
	•	Exposes PHP-FPM on port 9000.
	•	Code mounted from api/.

3. webdev — Vue Dev Server
	•	Runs the SPA during development.
	•	Exposed on port 5173.
	•	Code mounted from web/.

4. nginx — Reverse Proxy
	•	The only service your browser talks to (port 8080).
	•	Decides whether a request goes to:
	•	Laravel (/api/*)
	•	Vue dev server (everything else)
	•	Static files (/storage/*)

// Request Flow //

Browser (localhost:8080)
        │
        ▼
Nginx Reverse Proxy (default.conf)
        │
 ┌──────┴───────────────┐
 │                      │
 ▼                      ▼
Laravel API        Vue Frontend
(api/*)            (SPA pages)

// Architecture Pattern 

Laravel = API layer only
    - Routes live in api.php
    - Everything responds with JSON
    - URLs start with /api/*

Vue = Full SPA
    - Handles all front-end pages
    - Lives under /
    - Client-side routing via Vue Router
    - Nginx forwards everything except /api/* to Vue

Nginx = traffic router
    - Clean and predictable boundaries between backend and frontend