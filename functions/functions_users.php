<?php

// Savoir si un nom d'utilisateur est déjà utilisé
function UserExist($userName)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	if (dbConnect() !=0) {
		$req = "SELECT ID FROM `users` WHERE `Name` = '$userName'";
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

// Récupérer les infos d'un utilisateur
function GetUserInfos($userID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$userName = 'Inconnu';
	$userPass = 'nopass';
	$userRights = 2;
	$userMail = '';
	$userAvatar = GetAvatar('');
	$userDate = '';
	$userNom = '';
	$userPrenom = '';
	$userWebsite = '';
	$userSexe = '';
	$userBirthDate = '';
	$userPublicMail = '';
	if (dbConnect() != 0) {
		$req = "SELECT `ID`, `Name`, `Pass`, `Rights`, `Mail`, `Avatar`, `Date`, `Nom`, `Prenom`, `Website`, `Sexe`, `BirthDate`, `PublicMail` FROM `users` WHERE `ID` = '$userID'";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$userName = $rs[1];
			$userPass = $rs[2];
			$userRights = $rs[3];
			$userMail = $rs[4];
			$userAvatar = GetAvatar($rs[5]);
			$userDate = $rs[6];
			$userNom = $rs[7];
			$userPrenom = $rs[8];
			$userWebsite = $rs[9];
			$userSexe = $rs[10];
			$userBirthDate = $rs[11];
			$userPublicMail = $rs[12];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return array($userName, $userPass, $userRights, $userMail, $userAvatar, $userDate, $userNom, $userPrenom, $userWebsite, $userSexe, $userBirthDate, $userPublicMail);
}

// Récupérer les infos d'un utilisateur
function GetUserName($userID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$userName = 'Inconnu';
	if (dbConnect() != 0) {
		$req = "SELECT `Name` FROM `users` WHERE `ID` = '$userID'";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$userName = $rs[0];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $userName;
}

// Renvoie l'adresse de l'avatar d'un utilisateur
function GetAvatar($userAvatar)
{
	if ($userAvatar != '') {
		return $userAvatar;
	}
	else {
		return 'images/defaut.gif';
	}
}


// Récupérer les infos d'un utilisateur
function GetUserTabInfos($userID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$user['id'] = '0';
	$user['name'] = 'Inconnu';
	$user['pass'] = 'nopass';
	$user['rights'] = '2';
	$user['mail'] = '';
	$user['avatar'] = GetAvatar('');
	$user['date'] = '';
	$user['nom'] = '';
	$user['prenom'] = '';
	$user['website'] = '';
	$user['sexe'] = '';
	$user['birthdate'] = '';
	$user['publicmail'] = '';
	$user['actif'] = '1';
	if (dbConnect() != 0) {
		$req = "SELECT `ID`, `Name`, `Pass`, `Rights`, `Mail`, `Avatar`, `Date`, `Nom`, `Prenom`, `Website`, `Sexe`, `BirthDate`, `PublicMail`, `Actif` FROM `users` WHERE `ID` = '$userID'";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$user['id'] = $rs[0];
			$user['name'] = $rs[1];
			$user['pass'] = $rs[2];
			$user['rights'] = $rs[3];
			$user['mail'] = $rs[4];
			$user['avatar'] = GetAvatar($rs[5]);
			$user['date'] = $rs[6];
			$user['nom'] = $rs[7];
			$user['prenom'] = $rs[8];
			$user['website'] = $rs[9];
			$user['sexe'] = $rs[10];
			$user['birthdate'] = $rs[11];
			$user['publicmail'] = $rs[12];
			$user['actif'] = $rs[13];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $user;
}

?>
