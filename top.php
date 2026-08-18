<?
	// Pour le temps dexecution de la page
    list($usec, $sec) = explode(" ", microtime());
    $start_time=(float)$usec + (float)$sec;
	
	if (@$NotIncludeFonctions != 1) { include('functions.php'); }
	include 'forum/fonctions_forum.php';
	include('loguser.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html lang="fr" dir="ltr">
<!-- Date de création: 11/09/2006 -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title><?php echo $PageTitle; ?> - Vb System Library - VbSystemLibrary - VbSysLib - VSL</title>
	<meta name="author" content="Matthieu Napoli - MadMatt">
	<meta name="description" content="VbSystemLibrary - Vb System Library - Projet Commun Open Source en Visual Basic : Librairie de fonctions Visual Basic permettant d'agir simplement sur le système Windows. Architecture Objet.">
	<meta name="keywords" lang="fr" content="Visual Basic VB System Library Librairie Système VbSysLib VbSystemLibrary VSL VB6 VB5 Programmation Codes Sources Partage Open Source Windows Projet Architecture Objet Classes Classe Objets">
	<meta name="generator" content="MadMatt">
	<!-- L'icone du site -->
	<link rel="shortcut icon" href="images/icone.ico">
	<!-- Les feuilles de style -->
	<link rel="stylesheet" type="text/css" href="style.css">
	<?php
		// Les feuilles de style
		if (isset($IncludeCSS_Forum)) { echo '<link rel="stylesheet" type="text/css" href="forum/forum.css">'; }
		if (isset($IncludeCSS_RTE)) { echo '<link rel="stylesheet" type="text/css" href="conceptrte/style.css">'; }
		// Les scripts
		if (isset($LoadConceptRTE)) { echo '<script language="JavaScript" type="text/javascript" src="conceptrte/conceptRTE.js"></script>'; }
	?>
</head>

<body>

<!-- Le header -->
<div id="Header">
	<img src="images/header.jpg" height="120" alt="logo" style="border-style: none">
</div>


<!-- Contenu de la page -->
<div id="PageContent">

