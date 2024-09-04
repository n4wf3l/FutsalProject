# Utilise une image PHP officielle avec Composer
FROM php:8.1-fpm

# Installe les dépendances requises pour Composer et PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install zip pdo pdo_mysql

# Installe Composer globalement
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définit le répertoire de travail
WORKDIR /var/www/html

# Copie les fichiers du projet dans le conteneur Docker
COPY . .

# Installe les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader

# Installe les dépendances Node.js via npm
RUN npm install && npm run prod

# Expose le port pour l'accès HTTP
EXPOSE 80

# Commande pour démarrer Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
