# API

Base URL: `/api`

## Authentication

| Method | Endpoint | Description |
| --- | --- | --- |
| POST | `/register` | Create an account |
| POST | `/login` | Log in and receive a token |
| POST | `/logout` | Revoke the current token |
| GET | `/me` | Return the authenticated user |

## Mood Entries

Protected by Sanctum.

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/mood-entries` | List current user's mood entries |
| POST | `/mood-entries` | Create a mood entry |
| GET | `/mood-entries/{id}` | Show a mood entry |
| PUT/PATCH | `/mood-entries/{id}` | Update a mood entry |
| DELETE | `/mood-entries/{id}` | Delete a mood entry |

## Weather

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/weather/current` | Placeholder for future weather data |

## Dashboard

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/dashboard` | Minimal dashboard summary |
