PHP=franken_php

start:
	docker compose up -d --no-recreate --remove-orphans

stop:
	docker compose stop

down:
	docker compose down

recreate: down
	docker compose up -d --force-recreate

build:
	docker compose build

clear: down
	docker system prune
	docker builder prune
	docker image prune
	docker container prune
	docker volume prune
	docker network prune

up:
	docker compose up -d
	
stop-all:
	docker stop $$(docker ps -aq)
	
enter:
	docker compose exec $(PHP) bash

create-project:
	docker compose exec php composer create-project symfony/skeleton:8.0.* .

restart: stop start

install:
	docker compose exec php composer install
	docker compose exec php chmod -R 777 .
	docker compose exec php sh -c "cp .php-cs-fixer.dist.php .php-cs-fixer.php"

create-database:
	docker compose exec php php bin/console doctrine:database:create

drop-database:
	docker compose exec php php bin/console doctrine:database:drop --force

migrate-database:
	docker compose exec php php bin/console doctrine:migrations:migrate

fix:
	docker compose exec php ./vendor/bin/php-cs-fixer fix src --allow-risky=yes
