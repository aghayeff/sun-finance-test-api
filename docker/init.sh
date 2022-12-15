#!/bin/bash

FILE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

export DOCKER_DEFAULT_PLATFORM=linux/amd64
pushd "$FILE_DIR"

cp .env.docker ../.env

docker-compose exec api php bin/console doctrine:database:create
docker-compose exec api php bin/console doctrine:schema:update --force

docker-compose exec api php bin/console doctrine:database:create --env=test
docker-compose exec api php bin/console doctrine:schema:update --force --env=test

bash rebuild.sh

docker-compose exec api php bin/console lexik:jwt:generate-keypair

popd
