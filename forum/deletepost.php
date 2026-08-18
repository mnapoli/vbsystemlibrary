<?php
// Supprime un post
function DelPost($DeletePostID)
{
	global $IsConnected;
	$tempIsCo = $IsConnected;
	global $userID;
	global $userRights;
	// Vérifie que le commentaire existe bien
	if ($DeletePostID != 0) {
		list($postIDUser, $postIDParent, $postTitle, $postText, $postIDCateg, $postIDSubCateg, $postDate, $postTime) = GetPostInfos($DeletePostID);
		list($userName_tmp, $userPass_tmp, $userRights_tmp, $userMail_tmp, $userAvatar_tmp, $userDate_tmp, $userNom_tmp, $userPrenom_tmp, $userWebsite_tmp, $userSexe_tmp, $userBirthDate_tmp) = GetUserInfos($postIDUser);
		// Vérifie qu'on ai bien les droits
		if ($postIDUser and (($userRights == '1') or ($postIDUser == $userID))) {
			// Si c'est un post parent, on supprime les réponses
			$Replies = GetReplies($DeletePostID);
			if ($Replies) {
				foreach($Replies as $key => $deletepostID_tmp) {
					DelPost($deletepostID_tmp);
				}
			}
			if (dbConnect() != 0) {
				// Supprime le commentaire
				// $req = "DELETE FROM `forum` WHERE `ID` = '$DeletePostID' LIMIT 1";
				$req = "UPDATE `forum` SET `Actif` = '0' WHERE `ID` = '$DeletePostID' LIMIT 1";
				$result = mysql_query($req);
				if ($tempIsCo == 0) { dbClose(); }
			}
		}
	}
}
?>
