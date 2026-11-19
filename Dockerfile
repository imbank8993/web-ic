FROM wordpress:php8.3-apache

# Set working directory
WORKDIR /var/www/html

# Configure PHP settings for WordPress, Elementor & JetEngine
RUN { \
    echo 'memory_limit = 512M'; \
    echo 'upload_max_filesize = 128M'; \
    echo 'post_max_size = 128M'; \
    echo 'max_execution_time = 300'; \
    echo 'max_input_time = 300'; \
    echo 'max_input_vars = 5000'; \
    echo 'display_errors = Off'; \
    echo 'log_errors = On'; \
} > /usr/local/etc/php/conf.d/docker-php-wordpress-custom.ini

# Copy project files
COPY . /var/www/html/

# Set proper permissions for Apache & WordPress
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
