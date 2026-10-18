FROM wordpress:php8.3-apache

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Set proper permissions for Apache & WordPress
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && sed -i 's|^exec "$@"$|chown -R www-data:www-data /var/www/html/wp-content/uploads 2>/dev/null || true\nexec "$@"|' /usr/local/bin/docker-entrypoint.sh

EXPOSE 80
