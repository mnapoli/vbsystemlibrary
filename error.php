<?php
	include 'functions.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<!-- Date de création: 11/11/2004 -->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Erreur - Vb System Library</title>
		<meta name="author" content="Matthieu Napoli - MadMatt">
		<meta name="description" content="VbSystemLibrary - Vb System Library - Projet Commun Open Source en Visual Basic : Librairie de fonctions Visual Basic permettant d'agir simplement sur le système Windows. Architecture Objet.">
		<meta name="keywords" lang="fr" content="Visual Basic VB System Library Librairie Système VbSysLib VbSystemLibrary VSL VB6 VB5 Programmation Codes Sources Partage Open Source Windows Projet Architecture Objet Classes Classe Objets">
		<meta name="generator" content="MadMatt">
	</head>
	
<?php
	// Récupère la description
	if (isset($_GET['Descr'])) $descr = $_GET['Descr']; else $descr = '';
	// Récupère l'url
	if (isset($_GET['url'])) $url = $_GET['url']; else $url = $_SERVER['REQUEST_URI'];
	$date = date('d/m/Y');
	$time = date('H:i');
	// Ajout de l'erreur au log
	LogError($date . ' à ' . $time . ' | URL : ' . $url . "\r\n" . '    Description : ' . $descr, "log/errors.log");
?>
	
	<body>
		
		<div align="center"><div style="width: 85%; text-align: left">
			<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
			Une erreur est survenue, l'équipe Vb System Library vous présente ses excuses.<br /><br />
			L'erreur a été enregistrée.<br />
			<?php
				if (isset($_GET['Descr'])) {
				?>
				<br />La description de l'erreur est :<br />
				<blockquote class='clair'>
					<?php
						echo $_GET['Descr'];
					?>
				</blockquote>
				<?php
				}
			?>
			<br />
			<div style="text-align: center; font-weight: bold">
				Attention : si vous rafraichissez la page (avec F5 ou "Actualiser") vous retomberez sur cette page
				d'erreur, nous vous conseillons donc de choisir l'un des liens suivant :
			</div><br />
			<a href="javascript:history.go(-1)">Retourner à la page précédente</a><br />
			<a href="index.php">Retourner à la page d'accueil</a>
		</div></div>
		
	</body>
</html>

