.PHONY: install update down notify

NOTIFY_TOKEN=8709811015:AAFyr4TC0ql-OqsRfi4IDdo1vQVRmFjTOsg
NOTIFY_CHAT=-1004499878029

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
	$(MAKE) notify

down:
	docker-compose down

notify:
	notify:
	$(eval SERVER_IP := $(shell curl -s ifconfig.me))
	$(eval NOW := $(shell date '+%Y-%m-%d %H:%M:%S %Z'))
	$(eval COMMIT_MSG := $(shell git log -1 --pretty=%B | head -1))
	curl -s -X POST "https://api.telegram.org/bot$(NOTIFY_TOKEN)/sendMessage" \
		-d chat_id="$(NOTIFY_CHAT)" \
		-d text="🚀 CENP Internal Audit updated%0A📌 Change: $(COMMIT_MSG)%0A🖥 Server: $(SERVER_IP):8080%0A🕐 Time: $(NOW)" \
		-d parse_mode="Markdown"