# CENP Internal Audit — Deployment Guide

## Prerequisites

- Docker & docker-compose installed on server
- Port `8080` open on firewall

---

## 1. Clone the Repository

```bash
git clone https://github.com/HINCHEU/CENP-Internal-Audit.git
cd CENP-Internal-Audit
```

---

## 2. Create `.env` File

```bash
cp .env.example .env
nano .env
```

Set at minimum:

```env
APP_NAME=CENP
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://YOUR_SERVER_IP:8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=cenp
DB_USERNAME=cenp
DB_PASSWORD=secret
```

---

## 3. Fix Permissions

```bash
chmod -R 777 storage bootstrap/cache
chmod 666 .env
```

---

## 4. Build and Start Containers

```bash
docker-compose up -d --build
```

Wait for all containers to start, then run the node build:

```bash
docker rm -f cenp-node
docker-compose up node
```

Wait for `exited with code 0` then proceed.

---

## 5. Laravel Setup (run in order)

```bash
docker-compose exec -u root app php artisan key:generate
docker-compose exec -u root app php artisan migrate --force
docker-compose exec -u root app php artisan db:seed --class=DatabaseSeeder
docker-compose exec -u root app php artisan storage:link
docker-compose exec -u root app php artisan config:cache
docker-compose exec -u root app php artisan route:cache
docker-compose exec -u root app php artisan view:cache
```

---

## 6. Verify

```bash
docker-compose ps
```

Expected output:

| Name | State |
|------|-------|
| cenp-app | Up |
| cenp-nginx | Up |
| cenp-db | Up |
| cenp-node | Exit 0 |

Then open: `http://YOUR_SERVER_IP:8080`

---

## Default Login

| Field | Value |
|-------|-------|
| Email | `admin@cenp.com` |
| Password | `password` |

> ⚠️ Change the password immediately after first login.

---

## Troubleshooting

```bash
# View logs
docker-compose logs app
docker-compose logs nginx
docker-compose logs db

# Laravel log
docker-compose exec app tail -50 storage/logs/laravel.log

# Clear all caches
docker-compose exec -u root app php artisan cache:clear
docker-compose exec -u root app php artisan config:clear
docker-compose exec -u root app php artisan route:clear
docker-compose exec -u root app php artisan view:clear
```