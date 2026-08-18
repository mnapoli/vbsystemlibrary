<?php

// Ajoute un commentaire
function AddComm($codeID, $userID, $commText)
{
	if (!$commText) { return 0; }
	global $IsConnected;
	$tempIsCo = $IsConnected;
	if (dbConnect() != 0) {
		// Enregistre le commentaire
		$date = date('Y-m-d');
		$time = date('H:i');
		$IP = GetIP();
		$req = "INSERT INTO `comments` (`ID`, `User`, `Date`, `Time`, `Text`, `IP`, `IDcode`) VALUES ('', '$userID', '$date', '$time', '$commText', '$IP', '$codeID')";
		$result = mysql_query($req);
		if (!$result) { return 0; }
		if ($tempIsCo == 0) { dbClose(); }
		return 1;
	}
}

// Supprime un commentaire
function DelComm($commID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	global $userID;
	global $userRights;
	list($commUser, $commDate, $commTime, $commText, $commIP, $commIDcode) = GetCommInfos($commID);
	// Vérifie que le commentaire existe bien
	if ($commUser != 0) {
		list($commUserName, $commUserPass, $commUserRights, $commUserMail, $commUserAvatar, $commUserDate, $commUserNom, $commUserPrenom, $commUserWebsite, $commUserSexe, $commUserBirthDate) = GetUserInfos($commUser);
		// Vérifie qu'on ai bien les droits
		if (($userRights == '1') or ($commUser == $userID)) {
			if (dbConnect() != 0) {
				// Supprime le commentaire
				// $req = "DELETE FROM `comments` WHERE `ID` = '$commID' LIMIT 1";
				$req = "UPDATE `comments` SET `Actif` = '0' WHERE `ID` = '$commID'";
				$result = mysql_query($req);
				if ($tempIsCo == 0) { dbClose(); }
			}
		}
	}
}

// Récupérer la liste des commentaires d'un code
function GetCommFromCode($codeID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	if (dbConnect() != 0) {
		$req = "SELECT `ID` FROM `comments` WHERE `IDcode` = '$codeID' AND `Actif` = '1' ORDER BY `ID` ASC ";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$c=0;
			while ($rs = mysql_fetch_row($result)) {
				$tab[$c] = $rs[0];
				$c=$c+1;
			}
			if ($tempIsCo == 0) { dbClose(); }
			return $tab;
		}
		else {
			if ($tempIsCo == 0) { dbClose(); }
			return 0;
		}
	}
}

// Récupérer les infos d'un commentaire
function GetCommInfos($commID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$commUser = 'Inconnu';
	$commDate = '';
	$commTime = '';
	$commText = '';
	$commIP = '';
	$commIDcode = '';
	if (dbConnect() != 0) {
		$req = "SELECT `User`, `Date`, `Time`, `Text`, `IP`, `IDcode` FROM `comments` WHERE `ID` = '$commID'";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$commUser = $rs[0];
			$commDate = $rs[1];
			$commTime = $rs[2];
			$commText = $rs[3];
			$commIP = $rs[4];
			$commIDcode = $rs[5];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return array($commUser, $commDate, $commTime, $commText, $commIP, $commIDcode);
}

// Récupérer les infos d'un commentaire
function GetCommentTabInfos($commID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$comment['user'] = 'Inconnu';
	$comment['date'] = '';
	$comment['time'] = '';
	$comment['text'] = '';
	$comment['ip'] = '';
	$comment['idcode'] = '';
	if (dbConnect() != 0) {
		$req = "SELECT `User`, `Date`, `Time`, `Text`, `IP`, `IDcode` FROM `comments` WHERE `ID` = '$commID'";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$comment['user'] = $rs[0];
			$comment['date'] = $rs[1];
			$comment['time'] = $rs[2];
			$comment['text'] = $rs[3];
			$comment['ip'] = $rs[4];
			$comment['idcode'] = $rs[5];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $comment;
}

?>
