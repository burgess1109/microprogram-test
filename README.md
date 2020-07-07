# Currency Exchange Rate

## Local Development

- 下載專案

    `git clone git@github.com:burgess1109/laravel-sample.git`

- cp .env.example .env

    可調整 `DB_DATABASE` 、 `DB_USERNAME` 、 `DB_PASSWORD`

- 安裝 images & run containers

    `docker-composer up -d`

- 安裝 php 套件

    `docker-compose exec php-fpm composer install`

- Generate APP_KEY

    `docker-compose exec php-fpm php artisan key:generate`

- DB initialization

    `docker-compose exec php-fpm php artisan migrate:fresh --seed`

- 測試

    call http://localhost:8080/currency 

## 其他指令

- PSR-12 lint

    `docker-compose exec php-fpm ./vendor/bin/phpcs ./`

- run test

    `docker-compose exec php-fpm php artisan test`

## API 規格

詳 [openapi.yml](./openapi.yml)
 
