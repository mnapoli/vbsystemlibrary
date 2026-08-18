<?php
/**
 * Shim de compatibilité mysql_* -> SQLite (PDO), en lecture seule.
 *
 * Le code historique du site (2007) utilise l'extension `mysql` supprimee
 * depuis PHP 7. Plutot que de reecrire le site, on reimplemente en espace
 * utilisateur les seules fonctions reellement employees :
 *   mysql_connect, mysql_select_db, mysql_query, mysql_fetch_row,
 *   mysql_num_rows, mysql_close (+ mysql_error / mysql_insert_id inertes).
 *
 * Ce fichier est charge via `auto_prepend_file` : AUCUN fichier du site
 * n'est modifie. La base est un fichier SQLite embarque ; les ecritures
 * (INSERT/UPDATE/DELETE) sont ignorees (archive en lecture seule).
 *
 * NB : ce code s'execute sous PHP 5.6 (php-fpm legacy) -> syntaxe 5.6.
 */

if (!function_exists('mysql_query')) {

    /** Connexion SQLite partagee (ouverte paresseusement). */
    class VbSysLibDb
    {
        private static $pdo = null;

        public static function pdo()
        {
            if (self::$pdo === null) {
                $path = getenv('VBSYSLIB_SQLITE');
                if ($path === false || $path === '') {
                    $path = __DIR__ . '/../data/vbsyslib.sqlite';
                }
                $pdo = new PDO('sqlite:' . $path);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

                // Fonctions SQL propres a MySQL utilisees par le site.
                $pdo->sqliteCreateFunction('CONCAT', function () {
                    return implode('', array_map('strval', func_get_args()));
                });
                $pdo->sqliteCreateFunction('SUBSTRING', function ($s, $start, $len = null) {
                    if ($len === null) {
                        return substr((string) $s, $start - 1);
                    }
                    return substr((string) $s, $start - 1, $len);
                });

                self::$pdo = $pdo;
            }
            return self::$pdo;
        }
    }

    /** Resultat bufferise imitant une ressource mysql. */
    class VbSysLibResult
    {
        private $rows;
        private $pos = 0;

        public function __construct($rows)
        {
            $this->rows = $rows;
        }

        public function fetchRow()
        {
            if (isset($this->rows[$this->pos])) {
                return $this->rows[$this->pos++];
            }
            return false;
        }

        public function numRows()
        {
            return count($this->rows);
        }
    }

    function mysql_connect($server = null, $user = null, $pass = null)
    {
        // Ouvre (ou reutilise) la connexion SQLite ; renvoie un objet != 0.
        return VbSysLibDb::pdo();
    }

    function mysql_select_db($base = null, $link = null)
    {
        return true; // une seule base : le fichier SQLite
    }

    function mysql_query($sql, $link = null)
    {
        $pdo = VbSysLibDb::pdo();

        // Archive en lecture seule : les ecritures sont acceptees mais ignorees.
        if (preg_match('/^\s*(INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|ALTER|TRUNCATE)\b/i', $sql)) {
            return true;
        }

        $stmt = $pdo->query($sql);
        if ($stmt === false) {
            return false;
        }
        return new VbSysLibResult($stmt->fetchAll(PDO::FETCH_NUM));
    }

    function mysql_fetch_row($result)
    {
        return ($result instanceof VbSysLibResult) ? $result->fetchRow() : false;
    }

    function mysql_num_rows($result)
    {
        return ($result instanceof VbSysLibResult) ? $result->numRows() : 0;
    }

    function mysql_close($link = null)
    {
        return true;
    }

    function mysql_error($link = null)
    {
        $info = VbSysLibDb::pdo()->errorInfo();
        return isset($info[2]) ? $info[2] : '';
    }

    function mysql_insert_id($link = null)
    {
        return (int) VbSysLibDb::pdo()->lastInsertId();
    }
}
