# Utiliser PHP 8.4 avec Apache
FROM php:8.4-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurer Apache
RUN a2enmod rewrite
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . /var/www/html

# Définir APP_ENV pour éviter le chargement des bundles de dev
ENV APP_ENV=prod

# Installer d'abord AVEC les dépendances dev pour les scripts auto
RUN composer install --optimize-autoloader

# Nettoyer le cache et compiler les assets
RUN php bin/console cache:clear --env=prod --no-debug \
    && php bin/console asset-map:compile

# Maintenant supprimer les dépendances dev pour alléger l'image
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html/var

# Exposer le port 80
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]

