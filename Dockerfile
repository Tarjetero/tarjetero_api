FROM php:8.2-fpm-alpine

#set workspace
WORKDIR /var/www/html

#Install php extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions pdo_mysql bcmath redis calendar imagick zip xsl gd intl

# Change time zone
RUN apk add --update tzdata
RUN cp /usr/share/zoneinfo/America/Mexico_City /etc/localtime
RUN echo "America/Mexico_City" >  /etc/timezone
RUN date

# Configure nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Configure PHP
ADD docker/php.ini /usr/local/etc/php/php.ini
ADD docker/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini

# Configure supervisord
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Clean apk cache
RUN rm -rf /var/cache/apk/*

# Add application
COPY . /var/www/html/
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
RUN composer install


# Expose the port nginx is reachable on
EXPOSE 80

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
