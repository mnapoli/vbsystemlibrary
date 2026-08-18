<?

/*

	Lien vers le fichier zip de la version
	Parametres :
		id : ID de la version
		dll : Si 1 alors il s'agit du fichier DLL, si 2 il s'agit du zip complet

*/

include('functions.php');

// Récupère le numéro du tutoriel
@$versionID = $_GET['id'] or Error('Numéro de version à télécharger incorrect.');
@$IsDLL = $_GET['dll'] or Error('Lien incorrect.');

if (@dbConnect() !=0) {
	// Nombre de téléchargements
	$req = 'UPDATE `versions` SET `Clics` = `Clics` + 1 WHERE `ID` = ' . $versionID . ' LIMIT 1'; 
	$result = mysql_query($req);
	// Lien vers le fichier
	$req = 'SELECT `File`, `FileDLL` FROM `versions` WHERE `ID` = ' . $versionID . ' LIMIT 1';
	$result = mysql_query($req);
	$rs = mysql_fetch_row($result);
	if ($IsDLL == 1) {
		$Lien = $rs[1];
	}
	elseif ($IsDLL == 2) {
		$Lien = $rs[0];
	}
	dbClose();
	// on renvoi le visiteur vers le fichier
	Header('Location:' . $Lien);
}

?>