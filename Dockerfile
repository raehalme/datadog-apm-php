FROM php:8.1.5-apache
#Install git
RUN apt-get update \
    && apt-get install -y git
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite
#Install Datadog APM
RUN curl -sSfLo datadog-php-tracer.deb http://github.com/DataDog/dd-trace-php/releases/download/0.72.0/datadog-php-tracer_0.72.0_amd64.deb \
    && dpkg -i datadog-php-tracer.deb \
    && rm -f datadog-php-tracer.deb
#Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=. --filename=composer
RUN mv composer /usr/local/bin/
COPY src/php/ /var/www/html/
EXPOSE 80
