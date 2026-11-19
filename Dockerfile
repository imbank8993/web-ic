FROM wordpress:php8.3-apache

# Set working directory
WORKDIR /var/www/html

# Configure PHP settings & OPcache for WordPress, Elementor & JetEngine
RUN { \
    echo 'memory_limit = 512M'; \
    echo 'upload_max_filesize = 128M'; \
    echo 'post_max_size = 128M'; \
    echo 'max_execution_time = 300'; \
    echo 'max_input_time = 300'; \
    echo 'max_input_vars = 5000'; \
    echo 'display_errors = Off'; \
    echo 'log_errors = On'; \
    echo 'opcache.enable = 1'; \
    echo 'opcache.enable_cli = 0'; \
    echo 'opcache.memory_consumption = 256'; \
    echo 'opcache.interned_strings_buffer = 16'; \
    echo 'opcache.max_accelerated_files = 20000'; \
    echo 'opcache.revalidate_freq = 60'; \
    echo 'opcache.validate_timestamps = 1'; \
    echo 'opcache.fast_shutdown = 1'; \
} > /usr/local/etc/php/conf.d/docker-php-wordpress-custom.ini

# Copy project files
COPY . /var/www/html/

# Set proper permissions for Apache & WordPress (including cache dir)
RUN mkdir -p /var/www/html/wp-content/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/wp-content/cache

EXPOSE 80
