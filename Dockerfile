FROM php:8.2

WORKDIR /src/var/www


COPY . .

CMD ["php", "artisan", "serve","--host", "0.0.0.0"]
