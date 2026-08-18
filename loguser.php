<?
	// Pour se déconnecter
	if (isset($_GET['logout'])) {
		setcookie("visited", "", mktime()-3600, "/");
		$Logged = "false";
		$ErrorLogged = "Vous êtes déconnecté";
	}
	else {
		// Si le cookie n'existe pas on vérifie si l'utilisateur vient de se logguer
		if (isset($_POST['user']) and isset($_POST['pass'])) {
			$user = FormatText($_POST['user']);
			$pass = FormatText(strtolower($_POST['pass']));
			$passMD5 = md5($pass);
			if (!empty($user) and !empty($pass)) {
				// On vérifie les identités
				if (@dbConnect() !=0) {
					$temp=strtolower($user);
					$req = "SELECT `ID`, `Name`, `Pass` FROM `users` WHERE `Name` = '" . $temp . "' LIMIT 1"; 
					$result = mysql_query($req);
					// Si le résultat n'est pas vide
					if (!$result=="") {
						$nbresult = mysql_num_rows($result);
						// Si il n'y a pas de ligne ou plus d'une
						if ($nbresult==0 or $nbresult>1) {
							$Logged="false";
							$ErrorLogged="Pseudo Inconnu";
						}
						else {
							// Récupère le nom et le mot de passe
							$rs = mysql_fetch_row($result);
						    	$ID1 = $rs[0];
						    	$user1 = $rs[1];
						    	$pass1MD5 = $rs[2];
							dbClose();
							// Les mots de passe concordent, c'est le bon utilisateur
							if ($passMD5 == $pass1MD5) {
								$userID = $ID1;
								$userName = $user1;
								list($userName, $userPass, $userRights, $userMail, $userAvatar, $userDate, $userNom, $userPrenom, $userWebsite, $userSexe, $userBirthDate) = GetUserInfos($userID);
								$Logged = 'true';
								// Crée le cookie
								$CookieResult = setcookie('visited', $userName . '-' . $passMD5, mktime()+86400*30,"/");
								if ($CookieResult == 0) {
									$ErrorLogged='Impossible de créer un cookie';
									$Logged='false';
								}
							}
							else {
								$ErrorLogged="Mauvais mot de passe";
								$Logged="false";
							}
						}
					}
					else {
						// Resultat vide
						$Logged="false";
						$ErrorLogged="Mauvais login";
					}
				}
				else {
					// Connexion ratée à la base
					$Logged="false";
					$ErrorLogged="Connexion à la base de données impossible";
				}
			}
			else {
				// Login ou User vide
				$ErrorLogged="Remplissez tous les champs";
				$Logged="false";
			}
		}
		// Si on a pas cherché à se connecter on vérifie si un cookie existe
		else {
			if (!isset($_COOKIE['visited'])) {
				// On a pas cherché à se connecter : visiteur
				$ErrorLogged='';
				$Logged="false";
			}
			// Si le cookie existe
			else {
				$cookie = FormatText($_COOKIE['visited']);
				// Extrait le login et le password
				$len = strlen($cookie);
				$user = "";
				$passMD5 = "";
				$t=0;
				// Récupère user
				while($t < $len and !($cookie[$t] == "-"))
				{
					$user .= $cookie[$t];
					$t += 1;
				}
				$t += 1;
				// Récupère pass
				while($t < $len)
				{
					$passMD5 .= $cookie[$t];
					$t += 1;
				}
				// On vérifie les identités
				if (@dbConnect($dbServer, $dbUser, $dbPassword, $dbBase) !=0) {
					$temp=strtolower($user);
					$req = "SELECT `ID`, `Name`, `Pass` FROM `users` WHERE `Name` = '" . $temp . "'";
					$result = mysql_query($req);
					// Si le résultat n'est pas vide
					if ($result != "") {
						$nbresult = mysql_num_rows($result);
						if ($nbresult==0 or $nbresult>1) {
							$Logged="false";
							$ErrorLogged="Pseudo inconnu";
						}
						else {
							$ID1 = "";
							$pass1MD5 = "";
							// Récupère le nom et le mot de passe
							$rs = mysql_fetch_row($result);
					    		$ID1 = $rs[0];
					    		$pass1MD5 = $rs[2];
							dbClose();
							// Les mots de passe correspondent, c'est le bon utilisateur
							if ($passMD5 == $pass1MD5) {
								$userID = $ID1;
								$userName = $rs[1];
								list($userName, $userPass, $userRights, $userMail, $userAvatar, $userDate, $userNom, $userPrenom, $userWebsite, $userSexe, $userBirthDate) = GetUserInfos($userID);
								$Logged = "true";
							}
							else {
								$Logged = "false";
								$ErrorLogged = "Mauvais mot de passe";
							}
						}
					}
					else {
						// Resultat vide
						$Logged="false";
						$ErrorLogged="Pseudo Inconnu";
					}
				}
				else {
					// Connexion ratée à la base
					$Logged="false";
					$ErrorLogged="Connexion à la base de données impossible";
				}
			}
		}
	}
?>
