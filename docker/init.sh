#!/bin/bash

FILE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

export DOCKER_DEFAULT_PLATFORM=linux/amd64
pushd "$FILE_DIR"

cp .env.docker ../.env

docker-compose build
docker-compose up -d

docker-compose exec api composer install

docker-compose exec api php bin/console doctrine:database:create
docker-compose exec api php bin/console doctrine:schema:update --force

docker-compose exec api php bin/console doctrine:database:create --env=test
docker-compose exec api php bin/console doctrine:schema:update --force --env=test

docker-compose exec api php bin/console doctrine:migrations:migrate
docker-compose exec api php bin/console doctrine:fixtures:load

docker-compose exec api php bin/console doctrine:migrations:migrate --env=test
docker-compose exec api php bin/console doctrine:fixtures:load --env=test

docker-compose exec api php bin/console lexik:jwt:generate-keypair --overwrite

popd
