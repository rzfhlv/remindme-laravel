up:
	docker-compose up -d

down:
	docker-compose down

migrate:
	docker-compose exec php php artisan migrate

tinker:
	docker-compose exec php php artisan tinker

compose:
	docker-compose exec php composer install

queue:
	docker-compose exec php php artisan queue:work --tries=3

install:
	docker-compose exec php composer install \
	&& docker-compose exec php php artisan app:fresh-install