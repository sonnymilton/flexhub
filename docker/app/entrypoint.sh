#!/usr/bin/env bash
composer dump-env prod
bin/console cache:clear && chown www-data:www-data var/cache -R
bin/console doctrine:database:create --if-not-exists -n
bin/console doctrine:migrations:migrate -n --allow-no-migration
supervisord -c /etc/supervisor/supervisord.conf && supervisorctl reread && supervisorctl update && supervisorctl start messenger-consume:*
php-fpm
nginx
bash
