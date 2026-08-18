<?
	$PageTitle = "Votre Compte - Vb System Library";
	$xitiPageName = "Compte";
	include "top.php";
	include('functions/functions_graphics.php');
	list($userName, $userPass, $userRights, $userMail, $userAvatar, $userDate, $userNom, $userPrenom, $userWebsite, $userSexe, $userBirthDate, $userPublicMail) = GetUserInfos($userID);
?>

<!-- Contenu de la page -->

	<!-- Compte de l'utilisateur -->
	<?
		$WindowTitle = "Votre compte";
		include "windowtop.php";

			if ($Logged=='false') {
				Error('Vous devez être enregistré');
			}
			
			
			// Validation de la suppression de l'avatar
			if (isset($_GET['deleteavatar'])) {
				?>
					<p>
						Voulez-vous vraiment supprimer votre image personnelle ?
					</p>
					<form name="deleteAvatar_form" action="account.php" method="post">
						<input type="hidden" name="deleteAvatar" value="Yes">
						<input type="submit" value="Oui">
						<input type="button" value="Non" onclick="javascript:history.go(-1)">
					</form>
				<?php
			}
			// Suppression de l'avatar
			elseif (isset($_POST['deleteAvatar'])) {
				// Supprime le fichier
				if (($userAvatar != '') and ($userAvatar != 'images/defaut.gif') and (substr($userAvatar, 0, 7) != 'http://') and (substr($userAvatar, 0, 4) != 'www.')) {
					@unlink($userAvatar);
				}
				// Retire de la base de données
				if (@dbConnect() !=0) {
					$Requete = "UPDATE `users` SET `Avatar` = 'images/defaut.gif' WHERE CONCAT(`ID`) = $userID LIMIT 1";
					$Resultat = mysql_query($Requete);
					dbClose();
				}
				Redirect('account.php');
			}
			// Formulaire des modifications
			elseif (!isset($_POST['modifMail'])) {
				?>
				<form name="modif" action="account.php" method="post" enctype="multipart/form-data">
					<br />
					<!-- Les infos indispensables -->
					<div align="center"><div style="background: #2B295B; color: white; width: 90%; text-align: center">
						Informations indispensables
					</div></div><br />
					<font style="background: #E3E3F8">Pseudo</font> : <?php echo $userName; ?><br /><br />
					<font style="background: #E3E3F8">Utilisateur n°</font> <?php echo $userID; ?><br /><br />
					<font style="background: #E3E3F8">Enregistré le</font> <?php echo FormatDate($userDate); ?><br /><br />
					<font style="background: #E3E3F8">Votre statut est</font> : <?php echo GetRightsName($userRights); ?><br /><br />
					<font style="background: #E3E3F8">Adresse Mail</font> :<br />
						<div style="margin-left: 10%;"><input class="Clair" type="text" name="modifMail" value="<?php echo $userMail; ?>" size="40" maxlength="40" style="font: 10"></div><br />
					<font style="background: #E3E3F8">Modifier mon mot de passe</font> : <font size="-3">(si vous ne voulez pas le modifier, laissez les 3 cases vides)</font><br /><br />
					<div style="margin-left: 10%;">
						Mot de passe actuel :<br />
							<input class="Clair" type="password" name="modifPass" size="40" maxlength="256" style="font: 10"><br />
						Nouveau mot de passe :<br />
							<input class="Clair" type="password" name="modifPass1" size="40" maxlength="256" style="font: 10"><br />
						Confirmation :<br />
							<input class="Clair" type="password" name="modifPass2" size="40" maxlength="256" style="font: 10"><br />
					</div><br />
					
					<!-- Les infos personnelles -->
					<div align="center"><div style="background: #2B295B; color: white; width: 90%; text-align: center">
						Informations personnelles
					</div></div><br />
					<font style="background: #E3E3F8">Site Web</font> :<br />
						<div style="margin-left: 10%;"><input class="Clair" type="text" name="modifWebsite" value="<?php echo $userWebsite; ?>" size="40" maxlength="40" style="font: 10"></div><br />
					<font style="background: #E3E3F8">Nom</font> :<br />
						<div style="margin-left: 10%;"><input class="Clair" type="text" name="modifNom" value="<?php echo $userNom; ?>" size="40" maxlength="20" style="font: 10"></div><br />
					<font style="background: #E3E3F8">Prénom</font> :<br />
						<div style="margin-left: 10%;"><input class="Clair" type="text" name="modifPrenom" value="<?php echo $userPrenom; ?>" size="40" maxlength="20" style="font: 10"></div><br />
					<font style="background: #E3E3F8">Adresse Mail visible par les autres utilisateurs</font> :<br />
						<div style="margin-left: 10%;"><input class="Clair" type="text" name="modifPublicMail" value="<?php echo $userPublicMail; ?>" size="40" maxlength="40" style="font: 10"></div><br />
					<font style="background: #E3E3F8">Date de Naissance</font> :<br />
						<div style="margin-left: 10%;"><input class="Clair" type="text" name="modifBirthDate" value="<?php echo $userBirthDate; ?>" size="10" maxlength="10" style="font: 10"></div><br />
					<font style="background: #E3E3F8">Sexe</font> :<br />
						<div style="margin-left: 10%;">
							<input type="radio" name="modifSexe" value="Homme" <?php if ($userSexe=='Homme') { echo 'checked'; }?>> Homme<br />
							<input type="radio" name="modifSexe" value="Femme" <?php if ($userSexe=='Femme') { echo 'checked'; }?>> Femme<br />
							<input type="radio" name="modifSexe" value="Inconnu" <?php if ($userSexe=='') { echo 'checked'; }?>> Inconnu
						</div><br />
					<font style="background: #E3E3F8">Avatar</font> : <font size="-3">(l'avatar est l'image vous représentant)</font><br /><br />
						<div style="margin-left: 10%;">
							Image actuelle : <img src="<?php echo $userAvatar; ?>" alt=""><br /><br />
							<a href="account.php?deleteavatar">
								Supprimer l'image
							</a>
							<span style="font-size: 10px">
								(l'avatar par défaut sera utilisé)
							</span><br /><br />
							Modifier l'image : <span style="font-size: 10px">(laissez la case vide pour ne rien modifier)</span>
							<input class="Clair" type="file" name="modifAvatar" size="40">
							<!--<input class="Clair" name="modifAvatar" size="40" value="<?php echo $userAvatar; ?>">-->
						</div><br /><br />
					
					<div align="center"><input type="submit" value="Valider les modifications"></div>
					
	   		</form>
			<?php
			}
			// On enregistre les modifications
			else {
				$modifPass = FormatText(trim(strtolower($_POST['modifPass'])));
				$modifPass1 = FormatText(trim(strtolower($_POST['modifPass1'])));
				$modifPass2 = FormatText(trim(strtolower($_POST['modifPass2'])));
				$modifMail = FormatText(trim($_POST['modifMail']));
				$modifNom = FormatText(trim($_POST['modifNom']));
				$modifPrenom = FormatText(trim($_POST['modifPrenom']));
				$modifPublicMail = FormatText(trim($_POST['modifPublicMail']));
				$modifWebsite = FormatText(FormatUrl(trim($_POST['modifWebsite'])));
				$modifSexe = trim($_POST['modifSexe']);
				if ($modifSexe == 'Inconnu') { $modifSexe = ''; }
				$modifBirthDate = trim($_POST['modifBirthDate']);
				//$modifAvatar = FormatText(trim($_POST['modifAvatar']));
				$errorInfo = '';
				// Si soit les mots de passes sont tous vide, ou soit ils sont tous pleins
				if ((empty($modifPass) and empty($modifPass1) and empty($modifPass2)) or (!empty($modifPass) and !empty($modifPass1) and !empty($modifPass2))) {
					// C'est bon
				}
				else {
					$errorInfo .= 'Vous n\'avez pas rempli correctement les mots de passes : soit vous remplissez les 3 case, soit aucune des 3.<br />';
				}
				if ($modifPass1 != $modifPass2) {
					$errorInfo .= 'Vous n\'avez pas entré 2 mots de passes identiques.<br />';
				}
				// Vérifie le mot de passe
				if (!empty($modifPass) and $userPass != md5($modifPass)) {
					$errorInfo .= 'Vous n\'avez pas entré le bon mot de passe.<br />';
				}
				// L'adresse mail
				if ($modifMail == '') {
					$errorInfo .= 'Vous devez préciser une adresse mail valide.<br />';
				}
				
				// Sinon on modifie
				if ($errorInfo == '') {
					if (@dbConnect() !=0) {
					   	// Gère l'avatar
						$modifAvatar = $userAvatar;
					   	if (!empty($_FILES['modifAvatar']['size'])) {
						   	// On récupère la taille, le nom et le nom du fichier temporaire
							$fileAvatar_size = $_FILES['modifAvatar']['size'];
							$fileAvatar_name = $_FILES['modifAvatar']['name'];
							$fileAvatar_tmpname = $_FILES['modifAvatar']['tmp_name'];
							// Récupération de l'extension du fichier
							$fileAvatar_ext = strtolower(substr($fileAvatar_name, strrpos($fileAvatar_name, '.') + 1));
							// Vérifie l'extension
							if (!in_array($fileAvatar_ext, $avatarExtension)) $errorInfo = $errorInfo . 'Les types de fichiers pour l\'avatar sont incorrects<br>';
							if ($errorInfo == '') {
							   	// Supprime l'ancienne
							   	if (($userAvatar != '') and ($userAvatar != 'images/defaut.gif') and (substr($userAvatar, 0, 7) != 'http://') and (substr($userAvatar, 0, 4) != 'www.')) {
							   	   	unlink($userAvatar);
								}
								// l'image
								$modifAvatar = 'users/' . $userID . '.' . $fileAvatar_ext;
								// Déplace l'image
								move_uploaded_file($fileAvatar_tmpname, 'users/tmp/' . $userID . '.' . $fileAvatar_ext) or $errorInfo = $errorInfo . "Impossible d'enregistrer le fichier, contactez le webmaster<br>";
								// Redimensionne
								thumb('users/tmp/', $userID . '.' . $fileAvatar_ext, $avatarMaxDim, 'users/');
								// Supprime le temporaire
								unlink('users/tmp/' . $userID . '.' . $fileAvatar_ext);
							}
						}
						$reqPass = '';
						if (!empty($modifPass)) {
							// On crypte le mot de passe
							$modifPassMD5 = md5($modifPass1);
							$reqPass = "`Pass` = '$modifPassMD5', ";
						}
						// On enregistre le tout
						$Requete = "UPDATE `users` SET $reqPass`Mail` = '$modifMail', `Avatar` = '$modifAvatar', `Nom` = '$modifNom', `Prenom` = '$modifPrenom', `Website` = '$modifWebsite', `Sexe` = '$modifSexe', `BirthDate` = '$modifBirthDate', `PublicMail` = '$modifPublicMail' WHERE CONCAT(`ID`) = $userID LIMIT 1";
						$Resultat = mysql_query($Requete);
						dbClose();
						echo '<p>La modification de votre compte a été effectuée avec succès.<br /><br />
								<a href="account.php">Retourner aux options de mon compte</a><br />
								<a href="user.php?ID=' . $userID . '">Voir ma fiche</a></p>';
					}
					else {
						Error('Impossible de se connecter à la base');
					}
				}
				// Affiche les erreurs
				if ($errorInfo != '') {
					echo 'Des erreurs sont survenues pendant l\'enregistrement de votre compte :<br />';
					echo "<blockquote class='clair'>$errorInfo</blockquote>";
					echo '<a href="javascript:history.go(-1)">Recommencer</a>';
				}
			}

		include "windowbottom.php";
	?>

<?php include "bottom.php"; ?>
