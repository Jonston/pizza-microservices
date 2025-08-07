FROM php:8.4-fpm

# Получаем UID/GID из аргументов сборки
ARG USER_ID=1000
ARG GROUP_ID=1000

# Установка зависимостей
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

# Установка PHP расширений (добавлено sockets)
RUN docker-php-ext-install \
    pgsql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    sockets

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Синхронизация UID/GID с хостом
RUN groupmod -o -g ${GROUP_ID} www-data \
    && usermod -o -u ${USER_ID} -g www-data www-data

# Установка рабочей директории
WORKDIR /var/www/html

# Изменение прав на директории логов
RUN chown -R www-data:www-data /var/log/supervisor

# Создание директорий для логов приложения
RUN mkdir -p /var/log/app && chown -R www-data:www-data /var/log/app

# PHP-FPM слушает на порту 9000
EXPOSE 9000

# Для notification сервиса также экспонируем порт 8080 для Reverb
EXPOSE 8080

# Supervisor запускается от root, но процессы от www-data
USER root

# Команда по умолчанию - supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]