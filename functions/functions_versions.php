<?php

// Retourne la dernière version de la librairie
Function AddVersion($Version, $Msg, $File)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	if (dbConnect() != 0) {
		$req = "SELECT `Name` from versions ORDER BY ID desc LIMIT 1";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$retour = $rs[0];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $retour;
}

// Retourne la dernière version de la librairie
Function GetLastVersion()
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	if (dbConnect() != 0) {
		$req = "SELECT `Name` from versions ORDER BY ID desc LIMIT 1";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$retour = $rs[0];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $retour;
}

// Retourne l'ID de la dernière version de la librairie
Function GetLastVersionID()
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	if (dbConnect() != 0) {
		$req = "SELECT `ID` from versions ORDER BY ID desc LIMIT 1";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$retour = $rs[0];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $retour;
}

// Savoir si un numéro de version est déjà utilisé
function VersionExist($Version)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	if (dbConnect() !=0) {
		$req = "SELECT ID FROM `versions` WHERE `Name` = '$Version'";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$exist = 1;
		}
		else {
			$exist = 0;
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	else {
		$exist = -1;
	}
	return $exist;
}

// Retourne le nombre de fois qu'une version a été téléchargée
Function GetVersionDL($VersionID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	if (dbConnect() != 0) {
		$req = "SELECT `Clics` from versions WHERE `ID` = '$VersionID' LIMIT 1";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$retour = $rs[0];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $retour;
}

// Retourne le nombre de fois qu'une version a été téléchargée
Function GetNBTotalDL()
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$Total = 0;
	if (dbConnect() != 0) {
		$req = "SELECT `Clics` from versions";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		while ($rs = mysql_fetch_row($result)) {
			$Total = $Total + $rs[0];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $Total;
}

?>
