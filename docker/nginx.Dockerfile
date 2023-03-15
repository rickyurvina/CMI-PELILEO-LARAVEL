FROM nginx:1.15-alpine

WORKDIR /app

COPY ./docker/nginx.conf /etc/nginx/nginx.conf
