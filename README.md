# Sun Finance Test API

## Setup for Docker

The entire setup (initialization) of this docker project can be done by running this single script.
```shell
./docker/init.sh
```
You can now reach the application on http://localhost:9903

After the setup the database migrations will be run with fake data and the composer will be installed.

### Daily usage

Start the docker containers:
```shell
./docker/start.sh
```

Stop the docker containers:
```shell
./docker/stop.sh
```

### Update/Rebuild

```shell
./docker/rebuild.sh
```

### Install dependencies

Running composer

```sh
docker-compose exec api composer install
```

## Running tests
For executing all tests run
- `docker-compose exec api composer unit-test`
- `docker-compose exec api composer feature-test`

You can see the test logs inside tests/build/logs folder

## RabbitMQ
`http://localhost:15672`

- Username: `guest`
- password: `guest`

Running the queue via command:
```shell
docker-compose exec api php bin/console messenger:consume async
```

## Private API Authorization

`http://localhost:9903/api/login_check` 
- Username: `admin@example.com`
- password: `admin`

## Swagger Documentation
http://localhost:9903/api/doc