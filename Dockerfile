FROM php:7.0-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

RUN mkdir /var/www/html/log
RUN mkdir /var/www/tmp


RUN chown -R www-data:www-data /var/www/tmp && chmod -R 777 /var/www/tmp
RUN chown -R www-data:www-data /var/www/html/log && chmod -R 777 /var/www/html/log

RUN chown -R www-data:www-data /var/www/html 



COPY . /var/www/html/



EXPOSE 8080

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer






