# Database Schema

## users

Default Laravel users table.

Relevant fields:

- `id`
- `name`
- `email`
- `password`
- `timestamps`

## mood_entries

| Field | Type | Notes |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `user_id` | bigint | Owner |
| `entry_date` | date | Day being tracked |
| `mood` | string | Main mood label |
| `intensity` | tiny integer | 1 to 10 |
| `emotions` | json nullable | Optional emotion tags |
| `notes` | text nullable | User notes |
| `weather_summary` | string nullable | Future weather summary |
| `temperature` | decimal nullable | Future weather temperature |
| `weather_data` | json nullable | Future raw weather snapshot |
| `timestamps` | timestamps | Created and updated time |

Each user can have one mood entry per date.
