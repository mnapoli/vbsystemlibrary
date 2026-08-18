<?php
	$PageTitle = "S'inscrire";
	$xitiPageName = "Inscription";
	include "top.php";
	include('functions/functions_graphics.php');
?>

<!-- Contenu de la page -->
	<!-- S'inscrire -->
	<?php
		$WindowTitle = "S'inscrire";
		include "windowtop.php";
	?>
			<?php
				// Si on est à la confirmation d'inscription
				if (isset($_POST['inscrName'])) {
					$inscrName = FormatText(trim($_POST['inscrName']));
					$inscrPass1 = FormatText(trim(strtolower($_POST['inscrPass1'])));
					$inscrPass2 = FormatText(trim(strtolower($_POST['inscrPass2'])));
					$inscrMail = FormatText(trim($_POST['inscrMail']));
					$inscrPublicMail = FormatText(trim($_POST['inscrPublicMail']));
					$inscrNom = FormatText(trim($_POST['inscrNom']));
					$inscrPrenom = FormatText(trim($_POST['inscrPrenom']));
					$inscrWebsite = FormatUrl(trim($_POST['inscrWebsite']));
					$inscrSexe = trim($_POST['inscrSexe']);
					if ($inscrSexe == 'Non précisé') { $inscrSexe = ''; }
					$inscrBirthDate = trim($_POST['inscrBirthDate_year']) . '-' . trim($_POST['inscrBirthDate_month']) . '-' . trim($_POST['inscrBirthDate_day']);
					//$inscrAvatar = trim($_POST['inscrAvatar']);
					$errorInfo = '';
					if (empty($inscrName) or empty($inscrPass1) or empty($inscrPass2) or empty($inscrMail)) {
						$errorInfo .= 'Vous n\'avez pas rempli tous les champs obligatoires<br>';
					}
					if ($inscrPass1 != $inscrPass2) {
						$errorInfo .= 'Vous n\'avez pas entré 2 mots de passes indentiques<br>';
					}
					if (UserExist($inscrName) == 1) {
						$errorInfo .= 'Ce nom d\'utilisateur est déjà utilisé<br>';
					}
					// Pas d'erreurs : on enregistre
					if ($errorInfo == '') {
						if (@dbConnect() !=0) {
							// On gère l'avatar
							//if ($inscrAvatar=='') $inscrAvatar = 'images/defaut.gif';
							$inscrAvatar = 'images/defaut.gif';
							if (!empty($_FILES['inscrAvatar']['size'])) {
								// On récupère la taille, le nom et le nom du fichier temporaire
								$fileAvatar_size = $_FILES['inscrAvatar']['size'];
								$fileAvatar_name = $_FILES['inscrAvatar']['name'];
								$fileAvatar_tmpname = $_FILES['inscrAvatar']['tmp_name'];
								// Récupération de l'extension du fichier
								$fileAvatar_ext = strtolower(substr($fileAvatar_name, strrpos($fileAvatar_name, '.') + 1));
								// Vérifie l'extension
								if (!in_array($fileAvatar_ext, $avatarExtension)) $errorInfo = $errorInfo . 'Les types de fichiers sont incorrects<br>';
								if ($errorInfo == '') {
									// Récupère le numéro de fichier
									$req = 'SELECT Max(id) FROM users';
									$result = mysql_query($req);
									$rs = mysql_fetch_row($result);
									$tmpIDUser = $rs[0] + 1;
									// l'image
									$inscrAvatar = 'users/' . $tmpIDUser . '.' . $fileAvatar_ext;
									// Déplace l'image
									move_uploaded_file($fileAvatar_tmpname, 'users/tmp/' . $tmpIDUser . '.' . $fileAvatar_ext) or $errorInfo = $errorInfo . "Impossible d'enregistrer le fichier, contactez le webmaster<br>";
									// Redimensionne
									thumb('users/tmp/', $tmpIDUser . '.' . $fileAvatar_ext, $avatarMaxDim, 'users/');
									// Supprime le temporaire
									unlink('users/tmp/' . $tmpIDUser . '.' . $fileAvatar_ext);
								}
							}
							$date = date('Y-m-d');
							// On crypte le mot de passe
							$inscrPassMD5 = md5($inscrPass1);
							// On enregistre le tout
							$Requete = "INSERT INTO `users` (`ID`, `Name`, `Pass`, `Rights`, `Mail`, `Avatar`, `Date`, `Nom`, `Prenom`, `Website`, `Sexe`, `BirthDate`, `PublicMail`) VALUES ('', '$inscrName', '$inscrPassMD5', '2', '$inscrMail', '$inscrAvatar', '$date', '$inscrNom', '$inscrPrenom', '$inscrWebsite', '$inscrSexe', '$inscrBirthDate', '$inscrPublicMail')";
							$Resultat = mysql_query($Requete);
							dbClose();
							// Vérifie
							if (UserExist($inscrName) == 0) {
								$errorInfo .= 'Problème inconnu : l\'enregistrement a échoué<br>';
							}
							else {
								?>
									<p>Félicitations,<br />
									Vous avez été correctement enregistré, vous faites maintenant
									partie de la communauté Vb System Library !<br />
									<br />
									<a href="index.php">Retour à l'accueil</a><br /></p>
								<?php
							}
						}
						else { $errorInfo .= 'Connexion à la base de données impossible<br>'; }
					}
					// Affiche les erreurs
					if ($errorInfo != "") {
						echo '<p>Des erreurs sont survenues pendant l\'enregistrement de votre compte :</p>';
						echo "<blockquote class='clair'>$errorInfo</blockquote>";
						echo '<a href="javascript:history.go(-1)">Recommencer</a><br /><br />';
					}
				}
				// Si on est déjà enregistré
				elseif ($Logged=="true") {
			?>
				Vous êtes déjà inscrit<br>
			<?php
				}
				// Sinon on s'inscrit
				else {
			?>
				<p>Remplissez le formulaire pour pouvoir vous inscrire.<br /><br />
				<span style="font-weight: bold">Les champs marqués d'une <font color="#FF0000">*</font>
					 			sont obligatoires, les autres sont facultatifs.<br /></font></p>
				<form action="newuser.php" method="post" enctype="multipart/form-data">
					<table width="90%" align="center">
						<!-- Votre compte -->
						<tr bgcolor="#2B295B">
							<td colspan="2" align="center">
								<font color="#FFFFFF">
									Votre compte
								</font>
							</td>
						</tr>
						<!-- Pseudo -->
						<tr>
							<td style="background: #F0F1F3">
								Pseudo <font color="#FF0000">*</font> :
							</td>
							<td>
								<input class="Clair" type="text" name="inscrName" size="40" maxlength="40" style="font: 10">
							</td>
						</tr>
						<!-- Mot de passe -->
						<tr>
							<td style="background: #F0F1F3">
								Mot de passe <font color="#FF0000">*</font> :
							</td>
							<td>
								<input class="Clair" type="password" name="inscrPass1" size="40" maxlength="40" style="font: 10">
							</td>
						</tr>
						<!-- Mot de passe 2 -->
						<tr>
							<td style="background: #F0F1F3">
								Confirmation du mot de passe <font color="#FF0000">*</font> :
							</td>
							<td>
								<input class="Clair" type="password" name="inscrPass2" maxlength="40" size="40" style="font: 10">
							</td>
						</tr>
						<!-- Adresse Mail -->
						<tr>
							<td style="background: #F0F1F3">
								Adresse Mail <font color="#FF0000">*</font>
								<span style="font-size: 10px">Votre adresse mail ne sera diffusée à personne.</span> :
							</td>
							<td>
								<input class="Clair" type="text" name="inscrMail" maxlength="40" size="40" style="font: 10">
							</td>
						</tr>
						<tr>
						</tr>
						
						<!-- Informations personnelles -->
						<tr bgcolor="#2B295B">
							<td colspan="2" align="center">
								<font color="#FFFFFF">
									Informations personnelles (non obligatoires)
								</font>
							</td>
						</tr>
						<!-- Prénom -->
						<tr>
							<td style="background: #F0F1F3">
								Votre prénom (info rendue publique) :
							</td>
							<td>
								<input class="Clair" type="text" name="inscrPrenom" maxlength="20" size="40" style="font: 10">
							</td>
						</tr>
						<!-- Nom -->
						<tr>
							<td style="background: #F0F1F3">
								Votre nom (info rendue publique) :
							</td>
							<td>
								<input class="Clair" type="text" name="inscrNom" maxlength="20" size="40" style="font: 10">
							</td>
						</tr>
						<!-- Adresse Mail Publique -->
						<tr>
							<td style="background: #F0F1F3">
								Adresse Mail visible par les autres utilisateurs :
							</td>
							<td>
								<input class="Clair" type="text" name="inscrPublicMail" maxlength="40" size="40" style="font: 10">
							</td>
						</tr>
						<!-- Site Perso -->
						<tr>
							<td style="background: #F0F1F3">
								Votre site perso (si vous en avez un) :
							</td>
							<td>
								<input class="Clair" type="text" name="inscrWebsite" maxlength="40" size="40" style="font: 10" value="http://">
							</td>
						</tr>
						<!-- Avatar -->
						<tr>
							<td style="background: #F0F1F3">
								Votre image personnelle (avatar) :<br />
								<span style="font-size: 10px">L'image sera redimensionnée. Elle doit être du format jpg, gif ou png. Si vous n'en n'avez pas
									 			    		vous pouvez laisser la case vide.</span>
							</td>
							<td>
								<input class="Clair" type="file" name="inscrAvatar" size="40">
							</td>
						</tr>
						<!-- Sexe -->
						<tr>
							<td style="background: #F0F1F3">
								Vous êtes :
							</td>
							<td>
								<input type="radio" name="inscrSexe" value="Homme"> Homme<br>
					        		<input type="radio" name="inscrSexe" value="Femme"> Femme<br>
					        		<input type="radio" name="inscrSexe" value="Non précisé" checked> Non précisé<br>
							</td>
						</tr>
						<!-- Date de naissance -->
						<tr>
							<td style="background: #F0F1F3">
								Votre date de naissance (jour/mois/année) :
							</td>
							<td>
								<input class="Clair" type="text" name="inscrBirthDate_day" maxlength="2" size="2" style="font: 10"> /
								<input class="Clair" type="text" name="inscrBirthDate_month" maxlength="2" size="2" style="font: 10"> /
								<input class="Clair" type="text" name="inscrBirthDate_year" maxlength="4" size="4" style="font: 10">
							</td>
						</tr>
					</table><br />
					<font size="1">
						<font color="#FF0000">*</font> Champs obligatoires<br />
						<b>Vos informations personnelles seront rendues publiques, vous n'êtes pas obligés de les préciser.</b>
					</font><br /><br />
					<b><font color="#FF0000">Votre navigateur doit accepter les cookies pour pouvoir vous connecter.</font></b><br />
					<font size="-4"><i>Si vous ne savez pas ce qu'est un cookie, alors votre navigateur doit normalement les accepter ;).</i></font>
					<p>Cliquez ici pour vous inscrire :
					<input type="submit" value="Valider"></p>
				</form>
			<?php
				}
			?>
	<?php
		include "windowbottom.php";
	?>

<?php include "bottom.php"; ?>

