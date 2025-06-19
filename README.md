# app_coaster

## run app for dev

```sh
cp coaster/.env.development coaster/.env
docker compose -f docker/docker-compose.yml up -d
docker compose -f docker/docker-compose.yml exec coaster composer install
```

### run tests

```sh
docker compose -f docker/docker-compose.yml exec coaster ./vendor/bin/phpunit --no-coverage
```

## run app for prod

```sh
cp coaster/.env.development coaster/.env
docker compose -f docker/docker-compose.yml up -d
docker compose -f docker/docker-compose.yml exec coaster composer install --no-dev
```
