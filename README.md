# VbSystemLibrary — archive

Remise en ligne de [vbsystemlibrary.free.fr](http://vbsystemlibrary.free.fr/), le
premier site que j'ai écrit, en 2007 : une bibliothèque communautaire de code
Visual Basic (accès système Windows via API), avec forum, commentaires, news et
livre d'or.

Le site tournait en PHP 4/5 + MySQL sur les pages perso de Free. Après une mise à
jour de PHP côté Free en 2026, il renvoyait une erreur 500, et la base de données avait été
supprimée. Ce dépôt le fait revivre **en lecture seule**, sur AWS Lambda.

## Le principe : PHP serverless moderne portant du PHP legacy

Le code d'origine (2007) n'a pas été touché. Il utilise des balises courtes,
l'extension `mysql` supprimée depuis PHP 7, et une fonction nommée `GoTo`
(devenue mot réservé en PHP 5.3). Il exige donc **PHP 5.6** pour s'exécuter tel
quel, la seule retouche est le renommage mécanique `GoTo` → `Redirect`.

L'image Lambda fait cohabiter deux PHP :

```
Événement Lambda ─▶ Bref v3 (PHP 8.x, Amazon Linux 2023)
                      └─▶ runtime/handler.php
                            └─▶ Php56Proxy ──(CGI)──▶ php-cgi 5.6 ──▶ le site 2007
```

- **Bref** porte la boucle Lambda Runtime API sur un PHP 8 moderne.
- Le handler traduit la requête HTTP en appel **`php-cgi` 5.6** (compilé sur la
  même Amazon Linux 2023 pour la compatibilité binaire), sert les fichiers
  statiques, et reconstruit la réponse.
- La base MySQL d'origine est remplacée par un **fichier SQLite embarqué**. Un
  *shim* `mysql_*` → PDO/SQLite (chargé via `auto_prepend_file`, sans modifier le
  code du site) traduit les appels ; les écritures sont ignorées (lecture seule).

## Développement local

Faire tourner le site directement en PHP 5.6 + SQLite (sans la couche Lambda) :

```bash
docker run --rm -p 8099:8080 \
  -v "$PWD":/var/task -w /var/task \
  -e VBSYSLIB_SQLITE=/var/task/data/vbsyslib.sqlite \
  php:5.6-cli \
  php -c /var/task/config/php-legacy.ini -S 0.0.0.0:8080 -t /var/task
# http://localhost:8099/
```

Tester toute la chaîne Bref/PHP 8 → php-cgi 5.6 dans l'image de déploiement :

```bash
docker build -t vbsyslib:local .
docker run --rm -p 8099:8080 vbsyslib:local \
  /opt/bin/php runtime/local-server.php -S 0.0.0.0:8080   # serveur de test local
```
