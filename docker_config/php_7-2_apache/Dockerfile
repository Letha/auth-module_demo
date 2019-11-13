FROM php:7.2-apache
RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite && service apache2 restart
EXPOSE 80