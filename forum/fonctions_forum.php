<?php

// Récupérer les infos d'un commentaire
function GetPostInfos($postID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$postIDUser = '';
	$postIDParent = '';
	$postTitle = '';
	$postText = '';
	$postIDCateg = '';
	$postDate = '';
	$postTime = '';
	$postIP = '';
	if (dbConnect() != 0) {
		$req = "SELECT `IDUser`, `IDParent`, `Title`, `Text`, `IDCateg`, `Date`, `Time`, `IP` FROM `forum` WHERE `ID` = '$postID'";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$postIDUser = $rs[0];
			$postIDParent = $rs[1];
			$postTitle = $rs[2];
			$postText = $rs[3];
			$postIDCateg = $rs[4];
			$postDate = $rs[5];
			$postTime = $rs[6];
			$postIP = $rs[7];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return array($postIDUser, $postIDParent, $postTitle, $postText, $postIDCateg, $postDate, $postTime, $postIP);
}

// Récupérer les infos d'un commentaire
function GetPostTabInfos($postID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$post['iduser'] = '';
	$post['idparent'] = '';
	$post['title'] = '';
	$post['text'] = '';
	$post['idcateg'] = '';
	$post['date'] = '';
	$post['time'] = '';
	$post['ip'] = '';
	$post['actif'] = '';
	if (dbConnect() != 0) {
		$req = "SELECT `IDUser`, `IDParent`, `Title`, `Text`, `IDCateg`, `Date`, `Time`, `IP`, `Actif` FROM `forum` WHERE `ID` = '$postID'";
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult != 0) {
			$rs = mysql_fetch_row($result);
			$post['iduser'] = $rs[0];
			$post['idparent'] = $rs[1];
			$post['title'] = $rs[2];
			$post['text'] = $rs[3];
			$post['idcateg'] = $rs[4];
			$post['date'] = $rs[5];
			$post['time'] = $rs[6];
			$post['ip'] = $rs[7];
			$post['actif'] = $rs[8];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $post;
}

// Récupérer les infos d'un commentaire
function GetUserNBPost($userID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$UserNBPost = 0;
	if (dbConnect() != 0) {
		$req = "SELECT COUNT(*) FROM `forum` WHERE `IDUser` = '$userID' AND `Actif` = '1'";
		$result = mysql_query($req);
		if (mysql_num_rows($result) != 0) {
			$rs = mysql_fetch_row($result);
			$UserNBPost = $rs[0];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $UserNBPost;
}

// Récupérer le nombre de réponses
function GetNBReplies($postID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$NBReplies = 0;
	if (dbConnect() != 0) {
		$req = "SELECT COUNT(*) FROM `forum` WHERE `IDParent` = '$postID' AND `Actif` = '1'";
		$result = mysql_query($req);
		if (mysql_num_rows($result) != 0) {
			$rs = mysql_fetch_row($result);
			$NBReplies = $rs[0];
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $NBReplies;
}

// Récupérer les réponses
function GetReplies($postID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$Replies = null;
	if (dbConnect() != 0) {
		$req = "SELECT `ID` FROM `forum` WHERE `IDParent` = '$postID' AND `Actif` = '1' ORDER BY `ID` ASC";
		$result = mysql_query($req);
		if (mysql_num_rows($result) != 0) {
			$t = 0;
			while ($rs = mysql_fetch_row($result)) {
				$Replies[$t] = $rs[0];
				$t = $t + 1;
			}
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $Replies;
}

// Ajoute un message
function AddPost($postIDUSer, $postIDParent, $postTitle, $postText, $postIDCateg)
{
	if ($postText == '') return 0;
	if ($postIDUSer == '0') return 0;
	global $IsConnected;
	$tempIsCo = $IsConnected;
	if (dbConnect() != 0) {
		// Enregistre le commentaire
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$IP = GetIP();
		$req = "INSERT INTO `forum` (`ID`, `IDUser`, `IDParent`, `Title`, `Text`, `IDCateg`, `Date`, `Time`, `IP`, `Actif`) VALUES ('', '$postIDUSer', '$postIDParent', '$postTitle', '$postText', '$postIDCateg', '$date', '$time', '$IP', '1')";
		$result = mysql_query($req);
		if ($tempIsCo == 0) { dbClose(); }
		if (!$result) { return 0; }
		return 1;
	}
}

// Récupérer les catégories
function GetCateg()
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$Categ[0][0] = '';
	if (dbConnect() !=0) {
		$req = 'SELECT `ID`, `name`, `image` FROM `forumcateg` ORDER BY `name` ASC';
		$result = mysql_query($req);
		$t = 0;
		while ($rs = mysql_fetch_row($result)) {
			$Categ[$t][0] = $rs[0];
			$Categ[$t][1] = $rs[1];
			$Categ[$t][2] = $rs[2];
			$t = $t + 1;
		}
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $Categ;
}

// Renvoie le nom de la catégorie correspondante
function GetCatName($categID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$categName = $categID;
	if (dbConnect() != 0) {
		$req = "SELECT `name` FROM `forumcateg` WHERE `ID`='$categID'";
		$result = mysql_query($req);
		$rs = mysql_fetch_row($result);
		$categName = $rs[0];
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $categName;
}

// Renvoie le nom de la catégorie correspondante
function GetCatImage($categID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	$categImage = '';
	if (dbConnect() != 0) {
		$req = "SELECT `image` FROM `forumcateg` WHERE `ID`='$categID'";
		$result = mysql_query($req);
		$rs = mysql_fetch_row($result);
		$categImage = $rs[0];
		if ($tempIsCo == 0) { dbClose(); }
	}
	return $categImage;
}

?>
