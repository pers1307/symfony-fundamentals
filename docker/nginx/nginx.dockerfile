FROM nginx:latest

COPY ./docker/nginx/host.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/app
