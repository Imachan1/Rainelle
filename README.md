# Rainelle

Rainelle is a mood and weather tracking web app. Users can register, log in, add daily mood entries, save notes and emotions, connect weather data, and later view mood/weather insights with D3.js charts.

## Tech Stack

Backend:

- Laravel 12
- Laravel Sanctum
- REST API
- MySQL or PostgreSQL

Frontend:

- Vue 3
- Vue Router
- Pinia
- Axios
- SCSS
- D3.js planned later

## Project Structure

```text
rainelle/
|-- backend/
|-- frontend/
|-- docs/
|-- README.md
|-- DEPLOYMENT.md
`-- .gitignore
```

## Setup

Backend:

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Frontend:

```bash
cd frontend
npm install
cp .env.example .env
npm run dev
```

Default local URLs:

- Backend: `http://localhost:8000`
- Frontend: `http://localhost:5173`

## Current Status

- Laravel API backend is installed.
- Sanctum token authentication endpoints are prepared.
- Mood entry model, migration, requests, and API routes are prepared.
- Weather and dashboard endpoints have simple placeholders.
- Vue app has router, Pinia stores, Axios API clients, layouts, and simple views.
