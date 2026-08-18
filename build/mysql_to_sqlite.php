<?php
/**
 * Construit la base SQLite de l'archive à partir du MariaDB de build.
 *
 * - Recrée fidèlement les 9 tables (types simplifiés SQLite).
 * - Purge les données personnelles : emails, hash de mot de passe, IP.
 * - Nettoie le spam du livre d'or (par liste blanche d'IDs).
 *
 * Usage : php build/mysql_to_sqlite.php [chemin_sqlite]
 * Le MariaDB de build est attendu sur 127.0.0.1:3307 (conteneur jetable).
 */

$sqlitePath = $argv[1] ?? __DIR__ . '/../data/vbsyslib.sqlite';

$mysql = new PDO('mysql:host=127.0.0.1;port=3307;dbname=vbsystemlibrary;charset=utf8', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

@unlink($sqlitePath);
$sqlite = new PDO('sqlite:' . $sqlitePath, null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
$sqlite->exec('PRAGMA journal_mode = DELETE');

// Clé primaire par table (le reste des colonnes est introspecté).
$primaryKeys = [
    'codes' => 'ID', 'comments' => 'ID', 'forum' => 'ID', 'forumcateg' => 'ID',
    'news' => 'ID', 'stats' => 'Name', 'users' => 'ID', 'versions' => 'ID',
    'livreor' => 'ID',
];

function sqliteType(string $mysqlType): string
{
    $t = strtolower($mysqlType);
    if (str_contains($t, 'int')) return 'INTEGER';
    if (str_contains($t, 'float') || str_contains($t, 'double') || str_contains($t, 'decimal')) return 'REAL';
    return 'TEXT'; // varchar, text, enum, date, time -> TEXT
}

foreach ($primaryKeys as $table => $pk) {
    // 1. Introspection des colonnes
    $cols = $mysql->query("SHOW COLUMNS FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    $defs = [];
    $names = [];
    foreach ($cols as $c) {
        $names[] = $c['Field'];
        $def = '`' . $c['Field'] . '` ' . sqliteType($c['Type']);
        if ($c['Field'] === $pk) {
            $def .= ' PRIMARY KEY';
        }
        $defs[] = $def;
    }
    $sqlite->exec("CREATE TABLE `$table` (" . implode(', ', $defs) . ')');

    // 2. Copie des lignes (valeurs liées -> aucun problème d'échappement)
    $placeholders = implode(', ', array_fill(0, count($names), '?'));
    $colList = '`' . implode('`, `', $names) . '`';
    $insert = $sqlite->prepare("INSERT INTO `$table` ($colList) VALUES ($placeholders)");

    $rows = $mysql->query("SELECT $colList FROM `$table`");
    $n = 0;
    $sqlite->beginTransaction();
    foreach ($rows as $row) {
        $insert->execute(array_values($row));
        $n++;
    }
    $sqlite->commit();
    echo str_pad($table, 12) . " : $n lignes\n";
}

// 3. Purge des données personnelles
echo "\n-- Purge PII --\n";
// 3a. Emails, hash de mot de passe, IP.
$sqlite->exec("UPDATE users SET Mail = '', PublicMail = '', Pass = ''");
$sqlite->exec("UPDATE comments SET IP = ''");
$sqlite->exec("UPDATE forum SET IP = ''");
$sqlite->exec("UPDATE livreor SET Mail = ''");
echo "emails / hash / IP vidés (users, comments, forum, livreor)\n";

// 3b. Noms de famille et dates de naissance des tiers.
//     On conserve le prénom (déjà affiché publiquement à l'origine) et le nom
//     de famille du seul propriétaire du site (MadMatt, ID 1).
$ownerId = 1;
$sqlite->exec("UPDATE users SET Nom = '' WHERE ID <> $ownerId");
$sqlite->exec("UPDATE users SET BirthDate = '0000-00-00'");
echo "noms de famille vidés (sauf propriétaire ID $ownerId) ; dates de naissance effacées\n";

// 4. Nettoyage du spam du livre d'or (liste blanche des messages légitimes)
$legitLivreor = [1, 8, 42, 44];
$deleted = $sqlite->exec('DELETE FROM livreor WHERE ID NOT IN (' . implode(',', $legitLivreor) . ')');
echo "livre d'or : $deleted messages de spam supprimés, " . count($legitLivreor) . " conservés\n";

// 5. Vérifications finales
echo "\n-- Vérifications --\n";
$leaks = $sqlite->query("SELECT
    (SELECT COUNT(*) FROM users WHERE Mail<>'' OR PublicMail<>'' OR Pass<>'') AS users_pii,
    (SELECT COUNT(*) FROM comments WHERE IP<>'') AS comments_ip,
    (SELECT COUNT(*) FROM forum WHERE IP<>'') AS forum_ip")->fetch(PDO::FETCH_ASSOC);
echo "reste de PII (doit être 0) : users=" . $leaks['users_pii']
   . " comments_ip=" . $leaks['comments_ip']
   . " forum_ip=" . $leaks['forum_ip'] . "\n";

echo "SQLite écrit : $sqlitePath (" . number_format(filesize($sqlitePath)) . " octets)\n";
