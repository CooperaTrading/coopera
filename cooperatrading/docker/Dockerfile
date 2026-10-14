FROM composer:latest

# Use public DNS so Alpine package fetches resolve on hosts with broken
# internal DNS (apk update otherwise hangs with "DNS: transient error").
RUN echo "https://dl-cdn.alpinelinux.org/alpine/v3.24/main" > /etc/apk/repositories && \
    echo "https://dl-cdn.alpinelinux.org/alpine/v3.24/community" >> /etc/apk/repositories

RUN apk add --no-cache \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libwebp-dev \
        zlib-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql bcmath mysqli \
    && rm -rf /var/cache/apk/*

