FROM php:8.4-fpm

# Get UID/GID from build arguments
ARG USER_ID=1000
ARG GROUP_ID=1000

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    supervisor \
    && mkdir -p /var/log/supervisor \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (added sockets)
RUN docker-php-ext-install \
    pgsql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    sockets

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Sync UID/GID with host
RUN groupmod -o -g ${GROUP_ID} www-data \
    && usermod -o -u ${USER_ID} -g www-data www-data

# Set working directory
WORKDIR /var/www/html

# Change permissions for log directories
RUN chown -R www-data:www-data /var/log/supervisor

# Create directories for application logs
RUN mkdir -p /var/log/app && chown -R www-data:www-data /var/log/app

# Copy entrypoint script
COPY docker/services/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# PHP-FPM listens on port 9000
EXPOSE 9000

# For notification service also expose port 8080 for Reverb
EXPOSE 8080

# Supervisor runs as root, but processes as www-data
USER root

# Set entrypoint
ENTRYPOINT ["/entrypoint.sh"]

# Default command - supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]