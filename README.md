# TinyUrl

A URL shortener with authentication, custom short codes, and click tracking.

## Tech Stack

- PHP 8.3
- Laravel 13
- MySQL 8.4
- Vite 8 + Tailwind CSS 4

## Requirements

- PHP 8.3+
- Composer 2+
- Node.js 20+ and npm
- One of:
  - Docker + Docker Compose (recommended, via Laravel Sail)
  - A local MySQL server

## Start app
`./vendor/bin/sail up -d.`
`./vendor/bin/sail npm run dev`

## Staging Access Protection

When `APP_ENV=staging`, all web routes are protected by HTTP Basic Auth through middleware.

- Credentials are read from `STAGING_USER` and `STAGING_PASSWORD`
- If either is missing, the app returns `503` ("Staging credentials are not configured.")

## CI/CD and Deployment

This repository includes GitHub Actions workflows:

- `.github/workflows/deploy.yml`
  - Trigger: push to `main` or `staging`
  - Builds backend and frontend
  - Creates remote backup before deploy
  - Deploys to Hostinger via SSH + `rsync`
  - Runs `php artisan migrate --force`, `config:cache`, and `view:cache` on target
  - Uses Infisical on the remote host to inject runtime secrets

- `.github/workflows/restore-last-backup.yml`
  - Manual trigger via Github UI (`workflow_dispatch`)
