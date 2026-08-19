# syntax=docker/dockerfile:1

# ============================================================================
# VbSystemLibrary — archive en lecture seule sur AWS Lambda
# ============================================================================

# ---------------------------------------------------------------------------
# Étape 1 : compilation de php-cgi 5.6 sur Amazon Linux 2023
# ---------------------------------------------------------------------------
FROM amazonlinux:2023 AS php56builder

ARG PHP56_VERSION=5.6.40
RUN dnf -y groupinstall "Development Tools" \
 && dnf -y install gcc gcc-c++ make autoconf tar gzip findutils \
 && dnf clean all

# GCC 11 (AL2023) refuse par défaut les définitions communes du vieux code C
# de PHP 5.6 -> -fcommon rétablit l'ancien comportement.
ENV CFLAGS="-fcommon -O2" CXXFLAGS="-fcommon -O2"

WORKDIR /usr/src
RUN curl -fsSL "https://www.php.net/distributions/php-${PHP56_VERSION}.tar.gz" -o php.tar.gz \
 && tar xzf php.tar.gz

WORKDIR /usr/src/php-${PHP56_VERSION}
# SAPI CGI minimal : pcre + pdo_sqlite (sqlite embarqué) + mbstring.
# On désactive libxml/dom/etc. pour que le binaire ne dépende que de la glibc.
RUN ./configure \
      --prefix=/opt/php56 \
      --disable-all \
      --enable-cgi \
      --enable-pdo \
      --with-pdo-sqlite \
      --with-sqlite3 \
      --with-pcre-regex \
      --enable-mbstring \
      --enable-ctype \
      --enable-filter \
      --enable-session \
      --enable-tokenizer \
      --without-pear \
 && make -j"$(nproc)" \
 && make install \
 && /opt/php56/bin/php-cgi -v

# ---------------------------------------------------------------------------
# Étape 2 : dépendances Composer (Bref) sur PHP 8
# ---------------------------------------------------------------------------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ---------------------------------------------------------------------------
# Étape 3 : image finale Lambda (Bref, PHP 8) + php-cgi 5.6 + le site
# ---------------------------------------------------------------------------
FROM bref/php-83:3

# Le php-cgi 5.6 auto-suffisant (compilé sur la même AL2)
COPY --from=php56builder /opt/php56 /opt/php56

# Le code du site + la base SQLite + les configs, à l'emplacement Lambda
COPY . /var/task
COPY --from=vendor /app/vendor /var/task/vendor

ENV VBSYSLIB_DOCROOT=/var/task \
    VBSYSLIB_PHP56=/opt/php56/bin/php-cgi \
    VBSYSLIB_PHP_INI=/var/task/config/php-legacy.ini \
    VBSYSLIB_SQLITE=/var/task/data/vbsyslib.sqlite

# Runtime Bref v3 : "function" (notre classe HttpHandler, et non PHP-FPM).
# Requis par le bootstrap du conteneur Bref v3 (/opt/bref/bootstrap.php).
ENV BREF_RUNTIME=function

# Handler Bref (runtime "function") : runtime/handler.php
CMD ["runtime/handler.php"]
