<?php

// Récupérer les infos d'un code
function GetCodeTabInfos($codeID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$code['id'] = '0';
	$code['iduser'] = '0';
	$code['title'] = '';
	$code['presentation'] = '';
	$code['code'] = '';
	$code['date'] = '';
	$code['time'] = '';
	$code['historique'] = '';
	if (dbConnect() != 0) {
		$req = "SELECT `ID`, `IDuser`, `title`, `presentation`, `code`, `date`, `time`, `Historique` FROM `codes` WHERE `ID` = '$codeID'";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$code['id'] = $rs[0];
			$code['iduser'] = $rs[1];
			$code['title'] = $rs[2];
			$code['presentation'] = $rs[3];
			$code['code'] = $rs[4];
			$code['date'] = $rs[5];
			$code['time'] = $rs[6];
			$code['historique'] = $rs[7];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $code;
}

// Ajoute un élément à l'historique d'un code
function AddHistorique($codeID, $codeHistoriqueAdd, $userName)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	if (dbConnect() !=0) {
		$req = "SELECT `Historique` FROM `codes` WHERE `ID` = '$codeID'";
		$result = mysql_query($req);
		$rs = mysql_fetch_row($result);
		$br = '';
		if ($rs[0] != '') { $br = '<br />'; }
		$codeHistorique = $rs[0] . $br . 'Le ' . date('d/m/Y') . ' par ' . $userName . ' : ' . $codeHistoriqueAdd;
		$req = "UPDATE `codes` SET `Historique` = '$codeHistorique' WHERE `ID` = '$codeID'"; 
		$result = mysql_query($req);
		if ($tempIsCo == 0) { dbClose(); }
	}
}

?>
