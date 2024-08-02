FROM bitnami/laravel:11.1.4-debian-12-r0

WORKDIR /app
COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install 
RUN composer update

# TODO
# Esto se hace luego de montar en contenedor en el docker-compose
# RUN php artisan migrate:refresh --seed
# RUN php artisan l5-swagger:generate
# RUN php artisan passport:client --password
# RUN php artisan passport:client --personal
# copy: ./.env:/.env


CMD php artisan serve --host=0.0.0.0 --port=8000
EXPOSE 8000