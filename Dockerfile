# Utilise une image de base Laravel/PHP
FROM webdevops/php-nginx:8.1

# Définit le répertoire de travail
WORKDIR /var/www/html

# Copie les fichiers du projet dans le conteneur Docker
COPY . .

# Installe les dépendances PHP via Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Installe les dépendances Node.js via npm ou yarn
RUN npm install && npm run prod

# Expose le port pour l'accès HTTP
EXPOSE 80

# Commande pour démarrer Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
