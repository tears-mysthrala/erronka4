# HR Portal - Makefile

.PHONY: help install up down restart logs build migrate test lint

# Default target
help:
	@echo "HR Portal - Available commands:"
	@echo ""
	@echo "  make install     - Install PHP dependencies"
	@echo "  make up          - Start Docker containers"
	@echo "  make down        - Stop Docker containers"
	@echo "  make restart     - Restart Docker containers"
	@echo "  make logs        - View container logs"
	@echo "  make build       - Build Docker images"
	@echo "  make migrate     - Run database migrations"
	@echo "  make test        - Run tests"
	@echo "  make lint        - Run code linter"
	@echo "  make shell-php   - Open PHP container shell"
	@echo "  make shell-db    - Open PostgreSQL shell"
	@echo ""

# Install dependencies
install:
	@echo "Installing PHP dependencies..."
	composer install

# Start containers
up:
	@echo "Starting containers..."
	docker-compose -f docker-compose.hrportal.yml up -d
	@echo "Waiting for services to be ready..."
	@sleep 5
	@echo "Containers started!"
	@echo "Web: http://localhost:8080"

# Stop containers
down:
	@echo "Stopping containers..."
	docker-compose -f docker-compose.hrportal.yml down

# Restart containers
restart: down up

# View logs
logs:
	docker-compose -f docker-compose.hrportal.yml logs -f

# Build images
build:
	@echo "Building Docker images..."
	docker-compose -f docker-compose.hrportal.yml build --no-cache

# Run migrations
migrate:
	@echo "Running database migrations..."
	chmod +x ./scripts/migrate.sh
	./scripts/migrate.sh

# Run tests
test:
	@echo "Running tests..."
	./vendor/bin/phpunit

# Run linter
lint:
	@echo "Running code linter..."
	./vendor/bin/phpcs --standard=PSR12 src

# Fix code style
lint-fix:
	@echo "Fixing code style..."
	./vendor/bin/phpcbf --standard=PSR12 src

# Open PHP container shell
shell-php:
	docker-compose -f docker-compose.hrportal.yml exec php sh

# Open PostgreSQL shell
shell-db:
	docker-compose -f docker-compose.hrportal.yml exec postgres psql -U hr_user -d hr_portal

# Clean up
clean:
	@echo "Cleaning up..."
	rm -rf vendor/
	rm -rf logs/*.log
	rm -rf storage/temp/*
	docker-compose -f docker-compose.hrportal.yml down -v
