# Architecture

Rainelle is a small monorepo with a Laravel API backend and a Vue 3 frontend.

## Backend

The backend exposes REST API routes under `/api`. Laravel Sanctum is used for token-based authentication.

Main areas:

- `AuthController` handles register, login, logout, and current user endpoints.
- `MoodEntryController` handles mood entry CRUD.
- `WeatherController` is a placeholder for future weather integration.
- `DashboardController` returns a minimal dashboard summary.

## Frontend

The frontend is a Vue 3 app using Vue Router, Pinia, Axios, and SCSS.

Main areas:

- `src/api` contains API clients.
- `src/stores` contains Pinia stores.
- `src/views` contains route-level pages.
- `src/layouts` contains auth and app layouts.
