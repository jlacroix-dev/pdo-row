#!/usr/bin/env bash

docker compose run --rm php \
    composer install --no-interaction --prefer-dist

docker compose run --rm php \
    bash