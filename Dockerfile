FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsqlite3-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set Apache document root to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better Docker cache
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy package files and install Node dependencies
COPY package.json package-lock.json ./
RUN npm ci

# Copy the rest of the application
COPY . .

# Run composer scripts after full copy
RUN composer dump-autoload --optimize

# Build frontend assets
RUN npm run build

# Create SQLite database file
RUN mkdir -p database && touch database/database.sqlite

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Create the entrypoint script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
# Copy .env.production to .env if .env does not exist\n\
if [ ! -f .env ]; then\n\
    cp .env.production .env\n\
fi\n\
\n\
# Generate app key if not set\n\
if [ -z "$APP_KEY" ]; then\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Ensure SQLite database exists\n\
touch database/database.sqlite\n\
chown www-data:www-data database/database.sqlite\n\
chmod 664 database/database.sqlite\n\
\n\
# Run migrations and seed\n\
php artisan migrate --force\n\
php artisan db:seed --force 2>/dev/null || true\n\
\n\
# Cache config & routes\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Create storage symlink\n\
php artisan storage:link 2>/dev/null || true\n\
\n\
# Update Apache port to use Render PORT env var\n\
sed -i "s/80/${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf\n\
\n\
# Start Apache\n\
apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
