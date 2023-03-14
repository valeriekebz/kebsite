FROM php:7.0-apache
# Create a new user with a specific UID and GID RUN
 groupadd -r container && useradd --no-log-init -r -g container -u 1000 agent
 
RUN chown agent:container /app 
 # Switch to the new user
USER agent
 
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

RUN mkdir /var/www/html/log
RUN mkdir /var/www/tmp


RUN chown -R www-data:www-data /var/www/tmp && chmod -R 777 /var/www/tmp
RUN chown -R www-data:www-data /var/www/html/log && chmod -R 777 /var/www/html/log

RUN chown -R www-data:www-data /var/www/html && chmod -R 777 /var/www/html

 
 # Run some command as the new user CMD ["some-command"]

COPY . /var/www/html/



EXPOSE 8080

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer






