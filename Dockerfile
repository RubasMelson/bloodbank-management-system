FROM php:8.1-cli

WORKDIR /app

COPY . .

EXPOSE 8080
