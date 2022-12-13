#!/bin/bash

FILE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
pushd "$FILE_DIR"

docker-compose build
docker-compose up -d

docker-compose exec api composer install
docker-compose exec api php bin/console doctrine:migrations:migrate
docker-compose exec api php bin/console doctrine:fixtures:load

popd
