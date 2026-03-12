FROM nextcloud:33-apache

# Pre-populate the app so `occ app:enable` works immediately.
# Compose watch will keep this directory updated during development.
RUN mkdir -p /var/www/html/custom_apps/codeinjector && chown -R www-data:www-data /var/www/html/custom_apps

COPY --chown=www-data:www-data . /var/www/html/custom_apps/codeinjector
