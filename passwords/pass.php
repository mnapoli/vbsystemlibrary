<?php
// Configuration
// Dimensions max et poids max avatars
$avatarMaxDim = 80;
$avatarMaxSize = 61440; // 60Ko
$avatarExtension = array('jpg', 'jpeg', 'gif', 'png');
// La liste des tutoriels
$listcodesMax = 30; // 30 codes par pages
// La liste du forum
$listpostMax = 30; // 30 post par pages
// Les news
$listnewsMax = 5; // 5 news par pages
// Le livre d'or
$listlivreorMax = 10; // 10 messages par pages
// Taille max et extension librairie
$uploadMaxSize = 2097152; // 2Mo
$uploadExtension = array('zip');
// La liste des versions
$listversionsMax = 10; // 10 versions par pages

// Chemin absolu :
$TotalPath = $_SERVER["DOCUMENT_ROOT"] . '/';

/*
 * Archive en lecture seule : la base MySQL d'origine a été remplacée par un
 * fichier SQLite embarqué (voir le shim mysql_* dans config/). Ces variables
 * sont conservées car le code historique les déclare en global, mais elles ne
 * sont plus utilisées : la connexion réelle vise le fichier SQLite.
 */
$dbServer = '';
$dbBase = '';
$dbUser = '';
$dbPassword = '';
$SendMails = False;
