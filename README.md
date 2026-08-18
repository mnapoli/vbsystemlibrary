# VbSystemLibrary

Bringing back online [vbsystemlibrary.free.fr](http://vbsystemlibrary.free.fr/), the
first website I ever wrote, in 2007: a community library of Visual Basic code
(Windows system access through the API), with a forum, comments, news and a
guestbook.

The site ran on PHP 4/5 + MySQL on Free's personal hosting. After Free upgraded PHP
in 2026, it started returning a 500 error, and the database had been deleted. This
repository brings it back to life **read only**, on AWS Lambda.

## The idea: modern serverless PHP hosting legacy PHP

The original code (2007) has not been touched. It uses short tags, the `mysql`
extension removed since PHP 7, and a function named `GoTo` (which became a reserved
word in PHP 5.3). It therefore requires **PHP 5.6** to run as is, the only change
being the mechanical rename `GoTo` → `Redirect`.

The Lambda image runs two PHP versions side by side:

```
Lambda event ─▶ Bref v3 (PHP 8.x, Amazon Linux 2023)
                  └─▶ runtime/handler.php
                        └─▶ Php56Proxy ──(CGI)──▶ php-cgi 5.6 ──▶ the 2007 site
```

- **Bref** carries the Lambda Runtime API loop on a modern PHP 8.
- The handler translates the HTTP request into a **`php-cgi` 5.6** call (compiled on
  the same Amazon Linux 2023 for binary compatibility), serves static files, and
  rebuilds the response.
- The original MySQL database is replaced by an **embedded SQLite file**. A `mysql_*`
  to PDO/SQLite *shim* (loaded through `auto_prepend_file`, without touching the site
  code) translates the calls; writes are ignored (read only).

## Local development

Run the site directly on PHP 5.6 + SQLite (without the Lambda layer):

```bash
docker run --rm -p 8099:8080 \
  -v "$PWD":/var/task -w /var/task \
  -e VBSYSLIB_SQLITE=/var/task/data/vbsyslib.sqlite \
  php:5.6-cli \
  php -c /var/task/config/php-legacy.ini -S 0.0.0.0:8080 -t /var/task
# http://localhost:8099/
```

Test the full Bref/PHP 8 to php-cgi 5.6 chain in the deployment image:

```bash
docker build -t vbsyslib:local .
docker run --rm -p 8099:8080 vbsyslib:local \
  /opt/bin/php runtime/local-server.php -S 0.0.0.0:8080   # local test server
```
