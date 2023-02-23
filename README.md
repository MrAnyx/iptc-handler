# iptc-handler

```bash
docker build -t iptc-handler .
```

```bash
docker run -p 8088:80 -tid iptc-handler
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration --quiet
php bin/console doctrine:fixtures:load --no-interaction --quiet
```



Then visit `localhost:8088`

