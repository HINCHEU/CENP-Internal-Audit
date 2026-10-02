.PHONY: help install update restart down logs shell \
        db-backup db-restore db-shell \
        cache-clear notify

# ─── Configuration ────────────────────────────────────────────────────────────
COMPOSE        := docker compose
APP_SERVICE    := app
DB_CONTAINER   := cenp-db
DB_NAME        := cenp
DB_USER        := cenp
DB_PASS        := secret
BACKUP_DIR     := ./backups
NOTIFY_TOKEN   := 8709811015:AAFyr4TC0ql-OqsRfi4IDdo1vQVRmFjTOsg
NOTIFY_CHAT    := -1004499878029

# ─────────────────────────────────────────────────────────────────────────────
#  help  – list all available targets
# ─────────────────────────────────────────────────────────────────────────────
help:
	@echo ""
	@echo "  CENP Internal Audit – Makefile Commands"
	@echo "  ════════════════════════════════════════"
	@echo "  make install       Fresh install (first time setup)"
	@echo "  make update        Pull latest code + rebuild + migrate"
	@echo "  make restart       Restart all containers"
	@echo "  make down          Stop and remove all containers"
	@echo "  make logs          Tail app container logs"
	@echo "  make shell         Open bash shell in app container"
	@echo ""
	@echo "  make db-backup     Dump database to ./backups/"
	@echo "  make db-restore    Restore latest backup from ./backups/"
	@echo "  make db-shell      Open MySQL CLI in the db container"
	@echo ""
	@echo "  make cache-clear   Clear all Laravel caches"
	@echo "  make notify        Send Telegram deployment notification"
	@echo ""

# ─────────────────────────────────────────────────────────────────────────────
#  install  – first-time setup
# ─────────────────────────────────────────────────────────────────────────────
install:
	@echo "▶ Preparing environment..."
	cp -n .env.example .env || true
	chmod -R 777 storage bootstrap/cache
	chmod 666 .env

	@echo "▶ Building and starting containers..."
	$(COMPOSE) up -d --build

	@echo "▶ Building frontend assets..."
	docker rm -f cenp-node || true
	$(COMPOSE) up node

	@echo "▶ Installing PHP dependencies..."
	$(COMPOSE) exec -u root $(APP_SERVICE) composer install \
		--no-interaction --prefer-dist --optimize-autoloader --no-dev

	@echo "▶ Generating app key..."
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan key:generate

	@echo "▶ Running migrations..."
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan migrate --force

	@echo "▶ Seeding database..."
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan db:seed --class=DatabaseSeeder

	@echo "▶ Linking storage..."
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan storage:link

	@echo "▶ Caching config / routes / views..."
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan config:cache
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan route:cache
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan view:cache

	@echo "✅ Install complete! App is running at http://localhost:8080"

# ─────────────────────────────────────────────────────────────────────────────
#  update  – pull latest code, rebuild assets, run migrations
# ─────────────────────────────────────────────────────────────────────────────
update:
	@echo "▶ Backing up database before update..."
	$(MAKE) db-backup

	@echo "▶ Pulling latest code..."
	git pull origin main

	@echo "▶ Installing/updating PHP dependencies..."
	$(COMPOSE) exec -u root $(APP_SERVICE) composer install \
		--no-interaction --prefer-dist --optimize-autoloader --no-dev

	@echo "▶ Rebuilding frontend assets..."
	docker rm -f cenp-node || true
	$(COMPOSE) up node

	@echo "▶ Running migrations..."
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan migrate --force

	@echo "▶ Clearing and rebuilding caches..."
	$(MAKE) cache-clear
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan config:cache
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan route:cache
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan view:cache

	@echo "✅ Update complete!"
	$(MAKE) notify

# ─────────────────────────────────────────────────────────────────────────────
#  restart  – restart all containers
# ─────────────────────────────────────────────────────────────────────────────
restart:
	@echo "▶ Restarting containers..."
	$(COMPOSE) restart
	@echo "✅ Containers restarted."

# ─────────────────────────────────────────────────────────────────────────────
#  down  – stop and remove containers
# ─────────────────────────────────────────────────────────────────────────────
down:
	@echo "▶ Stopping containers..."
	$(COMPOSE) down
	@echo "✅ Containers stopped."

# ─────────────────────────────────────────────────────────────────────────────
#  logs  – tail app container logs (Ctrl+C to exit)
# ─────────────────────────────────────────────────────────────────────────────
logs:
	$(COMPOSE) logs -f $(APP_SERVICE)

# ─────────────────────────────────────────────────────────────────────────────
#  shell  – open bash in the app container
# ─────────────────────────────────────────────────────────────────────────────
shell:
	$(COMPOSE) exec -u root $(APP_SERVICE) bash

# ─────────────────────────────────────────────────────────────────────────────
#  cache-clear  – clear all Laravel framework caches
# ─────────────────────────────────────────────────────────────────────────────
cache-clear:
	@echo "▶ Clearing all caches..."
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan config:clear
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan route:clear
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan view:clear
	$(COMPOSE) exec -u root $(APP_SERVICE) php artisan cache:clear
	@echo "✅ Caches cleared."

# ─────────────────────────────────────────────────────────────────────────────
#  db-backup  – dump the database to ./backups/cenp_YYYY-MM-DD_HHMMSS.sql.gz
# ─────────────────────────────────────────────────────────────────────────────
db-backup:
	@mkdir -p $(BACKUP_DIR)
	$(eval BACKUP_FILE := $(BACKUP_DIR)/$(DB_NAME)_$(shell date '+%Y-%m-%d_%H%M%S').sql.gz)
	@echo "▶ Dumping database to $(BACKUP_FILE)..."
	docker exec $(DB_CONTAINER) mysqldump \
		-u $(DB_USER) -p$(DB_PASS) \
		--single-transaction \
		--routines \
		--triggers \
		$(DB_NAME) | gzip > $(BACKUP_FILE)
	@echo "✅ Backup saved: $(BACKUP_FILE)"
	@echo "   Available backups:"
	@ls -lh $(BACKUP_DIR)/*.sql.gz 2>/dev/null | tail -10 || true

# ─────────────────────────────────────────────────────────────────────────────
#  db-backup-plain  – same as db-backup but plain SQL (no gzip), easier to read
# ─────────────────────────────────────────────────────────────────────────────
db-backup-plain:
	@mkdir -p $(BACKUP_DIR)
	$(eval BACKUP_FILE := $(BACKUP_DIR)/$(DB_NAME)_$(shell date '+%Y-%m-%d_%H%M%S').sql)
	@echo "▶ Dumping plain SQL to $(BACKUP_FILE)..."
	docker exec $(DB_CONTAINER) mysqldump \
		-u $(DB_USER) -p$(DB_PASS) \
		--single-transaction \
		--routines \
		--triggers \
		$(DB_NAME) > $(BACKUP_FILE)
	@echo "✅ Backup saved: $(BACKUP_FILE)"

# ─────────────────────────────────────────────────────────────────────────────
#  db-restore  – restore the most recent backup from ./backups/
#  Usage:  make db-restore              (auto picks latest .sql.gz)
#          make db-restore FILE=backups/cenp_2025-01-01_120000.sql.gz
# ─────────────────────────────────────────────────────────────────────────────
db-restore:
	$(eval FILE ?= $(shell ls -t $(BACKUP_DIR)/*.sql.gz 2>/dev/null | head -1))
	@if [ -z "$(FILE)" ]; then \
		echo "❌ No backup file found in $(BACKUP_DIR)/. Pass FILE=path/to/backup.sql.gz"; \
		exit 1; \
	fi
	@echo "⚠️  About to restore: $(FILE)"
	@echo "   Press Ctrl+C within 5 seconds to cancel..."
	@sleep 5
	@echo "▶ Restoring database from $(FILE)..."
	@if echo "$(FILE)" | grep -q '\.gz$$'; then \
		gunzip -c $(FILE) | docker exec -i $(DB_CONTAINER) mysql \
			-u $(DB_USER) -p$(DB_PASS) $(DB_NAME); \
	else \
		docker exec -i $(DB_CONTAINER) mysql \
			-u $(DB_USER) -p$(DB_PASS) $(DB_NAME) < $(FILE); \
	fi
	@echo "✅ Restore complete."

# ─────────────────────────────────────────────────────────────────────────────
#  db-shell  – open MySQL CLI in the db container
# ─────────────────────────────────────────────────────────────────────────────
db-shell:
	docker exec -it $(DB_CONTAINER) mysql -u $(DB_USER) -p$(DB_PASS) $(DB_NAME)

# ─────────────────────────────────────────────────────────────────────────────
#  notify  – send Telegram deployment notification
# ─────────────────────────────────────────────────────────────────────────────
notify:
	$(eval SERVER_IP  := $(shell curl -s ifconfig.me))
	$(eval NOW        := $(shell date '+%Y-%m-%d %H:%M:%S %Z'))
	$(eval COMMIT_MSG := $(shell git log -1 --pretty=%B | head -1))
	curl -s -X POST "https://api.telegram.org/bot$(NOTIFY_TOKEN)/sendMessage" \
		-d chat_id="$(NOTIFY_CHAT)" \
		-d text="🚀 CENP Internal Audit updated%0A📌 Change: $(COMMIT_MSG)%0A🖥 Server: $(SERVER_IP):8080%0A🕐 Time: $(NOW)" \
		-d parse_mode="Markdown"