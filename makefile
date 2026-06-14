.PHONY: install update down

install:
	cp -n .env.example .env || true
	chmod -R 777 storage bootstrap/cache
	chmod 666 .env
	docker-compose up -d --build
	docker rm -f cenp-node || true
	docker-compose up node
	docker-compose exec -u root app composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
	docker-compose exec -u root app php artisan key:generate
	docker-compose exec -u root app php artisan migrate --force
	docker-compose exec -u root app php artisan db:seed --class=DatabaseSeeder
	docker-compose exec -u root app php artisan storage:link
	docker-compose exec -u root app php artisan config:cache
	docker-compose exec -u root app php artisan route:cache
	docker-compose exec -u root app php artisan view:cache

update:
	git pull origin main
	docker-compose exec -u root app composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
	docker rm -f cenp-node || true
	docker-compose up node
	docker-compose exec -u root app php artisan migrate --force
	docker-compose exec -u root app php artisan config:cache
	docker-compose exec -u root app php artisan route:cache
	docker-compose exec -u root app php artisan view:cache

down:
	docker-compose down