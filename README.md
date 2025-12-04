# PHP 8.3 + Nginx + MySQL Docker setup

This repository provides a minimal Docker Compose stack to run PHP 8.3 with Nginx and MySQL.

## Requirements
- Docker
- Docker Compose v2

## Usage
1. Copy optional environment overrides:
   ```bash
   cp .env.example .env # edit values as needed
   ```

2. Start the stack:
   ```bash
   docker compose up --build
   ```

3. Open the demo page at http://localhost:8080. It shows the PHP version and attempts a database connection.

## Configuration
- Nginx listens on port `8080` (mapped to container port `80`).
- MySQL listens on port `3306` and stores data in the `db_data` volume.
- Default database credentials are defined with safe fallbacks in `docker-compose.yml` and can be overridden via environment variables.

## Project structure
- `docker-compose.yml` — service definitions for PHP-FPM, Nginx, and MySQL.
- `docker/php/Dockerfile` — builds the PHP 8.3 FPM image with PDO extensions.
- `docker/nginx/Dockerfile` — builds a lightweight Nginx image that bakes in the virtual host config.
- `docker/nginx/default.conf` — Nginx vhost that forwards PHP requests to the PHP-FPM service.
- `src/public/index.php` — simple landing page that tests PHP and database connectivity.
- `.gitignore` — ignores local environment files and common dependency folders.
