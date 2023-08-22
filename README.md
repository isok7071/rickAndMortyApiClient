# Деплой

1. `docker compose up`
2. Зайти в контейнер с php-fpm и в корне проекта

```
composer install
```

3. Готово.
   Адрес бэкэнда и эндпоинт - http://localhost/

Пример запроса:

```
{
    "id":1,
    "params":{
        "id":1
        },
    "method":"getLocationsByIds"
}
```
