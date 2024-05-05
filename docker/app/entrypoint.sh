#!/usr/bin/env bash
composer dump-env prod
bin/console cache:clear
bin/console doctrine:database:create --if-not-exists -n
bin/console doctrine:migrations:migrate -n --allow-no-migration
php-fpm
nginx
bin/console messenger:consume async --time-limit=3600
bash
