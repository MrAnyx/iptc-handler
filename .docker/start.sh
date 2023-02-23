php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration --quiet
php bin/console doctrine:fixtures:load --no-interaction --quiet
exec apache2-foreground