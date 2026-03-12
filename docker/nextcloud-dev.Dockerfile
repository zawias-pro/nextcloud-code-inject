ARG BASE_IMAGE
FROM ${BASE_IMAGE}

RUN mkdir -p /var/www/html/custom_apps/codeinjector \
    && chown -R www-data:www-data /var/www/html/custom_apps

COPY --chown=www-data:www-data \
    . \
    /var/www/html/custom_apps/codeinjector
