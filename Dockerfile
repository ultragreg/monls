FROM php:5.5-apache

# PDO (cf. https://github.com/docker-library/php/issues/62)
RUN docker-php-ext-install pdo pdo_mysql

# valeurs par défaut dev
# Utilisation du legacy --link, TODO : passer à docker networking
ENV DB_HOSTNAME db
ENV DB_NAME monls
ENV DB_USERNAME monls
ENV DB_PASSWORD monls

ADD . /var/www/html/
