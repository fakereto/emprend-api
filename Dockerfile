FROM fakereto/nginx-fpm:latest
# add bitbucket and github to known hosts for ssh needs
WORKDIR /root/.ssh

# install composer so we can run dump-autoload at entrypoint startup in dev
# copied from official composer Dockerfile
ENV PATH="/composer/vendor/bin:$PATH" \
    COMPOSER_ALLOW_SUPERUSER=1

RUN chmod 0600 /root/.ssh \
    && ssh-keyscan -t rsa bitbucket.org >> known_hosts \
    && ssh-keyscan -t rsa github.com >> known_hosts \
    && phpdismod xdebug

#Default env for lumen framework
ENV APP_NAME=emprende-api \
    APP_ENV="development" \
    APP_DEBUG=true \
    APP_LOG=stderr \
    APP_URL=http://localhost \
    DB_CONNECTION=mysql \
    DB_HOST=db \
    DB_PORT=3306 \
    DB_DATABASE=api \
    DB_USERNAME=apiuser \
    CACHE_DRIVER=file \
    LOG_CHANNEL=stderr \
    LOG_SLACK_WEBHOOK_URL=NONE \
    ENV_SERVER_NAME=general.neubox.com \
    SWITCH_ON=true

COPY ./configs/app.conf ${NGINX_CONF_DIR}/sites-enabled/app.conf

WORKDIR /var/www/app
COPY  ./src .

#Install composer dependeces
RUN rm -rf vendor && composer install --prefer-dist --no-scripts --no-dev --optimize-autoloader

COPY entrypoint.sh /var/www/
ENTRYPOINT ["/var/www/entrypoint.sh"]

EXPOSE 80 443
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]%