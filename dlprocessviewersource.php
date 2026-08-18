<?

include('functions.php');

// Nombre de téléchargements
if (@dbConnect() !=0) {
	$req = "UPDATE `stats` SET `Value` = `Value` + 1 WHERE `Name` = 'DLProcessViewerSource'"; 
	$result = mysql_query($req);
	dbClose();
}

// on renvoi le visiteur vers le fichier
Header("Location:process-viewer/ProcessViewer-VbSysLib.zip"); 

?>
