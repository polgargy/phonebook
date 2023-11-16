include .env

#-----------------------------------------------------------
# Docker
#-----------------------------------------------------------

# Wake up docker containers
up:
	docker-compose up -d

# Shut down docker containers
down:
	docker-compose down

# Show a status of each container
status:
	docker-compose ps

# Status alias
s: status

# Show logs of each container
logs:
	docker-compose logs

# Watch node output
logs-n:
	docker logs -f ${NODE_CONTAINER}

# Restart all containers
restart: down up

# Restart the node container
restart-node:
	docker-compose restart node

# Restart the node container alias
rn: restart-node

# Show the node logs
logs-node:
	docker-compose logs node

# Show the node logs alias
ln: logs-node

# Build and up docker containers
build:
	docker-compose up -d --build

# Build containers with no cache option
build-no-cache:
	docker-compose build --no-cache

# Build and up docker containers
rebuild: down build

deploy: rebuild composer-install-t migrate-force restart-node

# Run terminal of the php container
php:
	docker-compose exec -u1000 php bash

# Run terminal of the node container
node:
	docker-compose exec -u1000 node /bin/sh


#-----------------------------------------------------------
# Logs
#-----------------------------------------------------------

# Clear file-based logs
logs-clear:
	sudo rm docker/${DOCKER_ENV}/nginx/logs/*.log
	sudo rm docker/${DOCKER_ENV}/supervisor/logs/*.log
	sudo rm storage/logs/*.log


#-----------------------------------------------------------
# Database
#-----------------------------------------------------------

# Run database migrations
db-migrate:
	docker-compose exec -u1000 php php artisan migrate

# Migrate alias
migrate: db-migrate

migrate-force:
	docker-compose exec -u1000 -T php php artisan migrate --force

# Run migrations rollback
db-rollback:
	docker-compose exec -u1000 php php artisan migrate:rollback

# Rollback alias
rollback: db-rollback

# Run seeders
db-seed:
	docker-compose exec -u1000 php php artisan db:seed

# Fresh all migrations
db-fresh:
	docker-compose exec -u1000 php php artisan migrate:fresh


#-----------------------------------------------------------
# Redis
#-----------------------------------------------------------

redis:
	docker-compose exec -u1000 redis redis-cli

redis-flush:
	docker-compose exec -u1000 redis redis-cli FLUSHALL

redis-install:
	docker-compose exec -u1000 php composer require predis/predis


#-----------------------------------------------------------
# Queue
#-----------------------------------------------------------

# Restart queue process
queue-restart:
	docker-compose exec -u1000 php php artisan queue:restart


#-----------------------------------------------------------
# Testing
#-----------------------------------------------------------

# Run phpunit tests
test:
	docker-compose exec -u1000 php vendor/bin/phpunit --order-by=defects --stop-on-defect

# Run all tests ignoring failures.
test-all:
	docker-compose exec -u1000 php vendor/bin/phpunit --order-by=defects

# Run phpunit tests with coverage
coverage:
	docker-compose exec -u1000 php vendor/bin/phpunit --coverage-html tests/report

# Run phpunit tests
dusk:
	docker-compose exec -u1000 php php artisan dusk

# Generate metrics
metrics:
	docker-compose exec -u1000 php vendor/bin/phpmetrics --report-html=tests/metrics app

php-check: phpcbf phpstan
php-fix: phpcbf

phpcs:
	docker-compose exec -u1000 php vendor/bin/phpcs -n

phpcbf:
	docker-compose exec -u1000 php vendor/bin/phpcbf -n

phpstan:
	docker-compose exec -u1000 php vendor/bin/phpstan analyse

node-fix:
	docker-compose exec -u1000 node npm run lint


#-----------------------------------------------------------
# Dependencies
#-----------------------------------------------------------

# Install composer dependencies
composer-install:
	docker-compose exec -u1000 php composer install

composer-install-t:
	docker-compose exec -u1000 -T php composer install

# Update composer dependencies
composer-update:
	docker-compose exec -u1000 php composer update

# Update npm dependencies
npm-update:
	docker-compose exec -u1000 node npm update

# Update all dependencies
dependencies-update: composer-update npm-update

# Show composer outdated dependencies
composer-outdated:
	docker-compose exec -u1000 npm outdated

# Show npm outdated dependencies
npm-outdated:
	docker-compose exec -u1000 npm outdated

# Show all outdated dependencies
outdated: npm-update composer-outdated


#-----------------------------------------------------------
# Tinker
#-----------------------------------------------------------

# Run tinker
tinker:
	docker-compose exec -u1000 php php artisan tinker


#-----------------------------------------------------------
# Installation
#-----------------------------------------------------------

# Add permissions for Laravel cache and storage folders
permissions:
	sudo chown -R $$USER:www-data storage
	sudo chown -R $$USER:www-data bootstrap/cache
	sudo chmod -R 775 bootstrap/cache
	sudo chmod -R 775 storage

# Permissions alias
perm: permissions

# Generate a Laravel key
key:
	docker-compose exec -u1000 php php artisan key:generate --ansi

# Generate a Laravel storage symlink
storage:
	docker-compose exec -u1000 php php artisan storage:link

# PHP composer autoload command
autoload:
	docker-compose exec -u1000 php composer dump-autoload

# Install the environment
install: build composer-install key storage permissions migrate rn
install-win: build composer-install key storage migrate rn


#-----------------------------------------------------------
# Clearing
#-----------------------------------------------------------

# Shut down and remove all volumes
remove-volumes:
	docker-compose down --volumes

# Remove all existing networks (useful if network already exists with the same attributes)
prune-networks:
	docker network prune
