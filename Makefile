.PHONY: build up down help

help:
	@echo "Available targets:"
	@echo "  build    - Build the Docker image"
	@echo "  up       - Start the application"
	@echo "  down     - Stop the application"
	@echo "  help     - Show this help message"

build:
	docker build -t smalltime .

up:
	docker compose up -d

down:
	docker compose down
