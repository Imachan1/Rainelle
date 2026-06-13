# Deployment

Rainelle is not production-ready yet. This file tracks the planned deployment shape.

## Backend

- Configure production `.env`.
- Set `APP_ENV=production`.
- Set `APP_DEBUG=false`.
- Configure MySQL or PostgreSQL.
- Run migrations.
- Point CORS to the deployed frontend URL.

## Frontend

- Set `VITE_API_URL` to the deployed backend API URL.
- Run `npm run build`.
- Deploy the generated static assets.

## Notes

D3 visualizations and weather provider integration are planned later and are not part of the initial setup.
