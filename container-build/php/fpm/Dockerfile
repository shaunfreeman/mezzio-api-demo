FROM shaunfreeman/php:7.4-fpm

ARG user
ARG uid

# RUN rm /usr/local/etc/conf.d/10-opcache.ini

RUN useradd -u $uid $user \
    && echo "[www]" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "user = $user" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "group = $user" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && mkdir /home/cms && chown $user:$user /home/cms
