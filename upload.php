<?php
	$PageTitle = 'Mettre à jour la librairie';
	include "top.php";
	// Vérifie l'identité de l'utilisateur
	if (($Logged=='false') or ($userRights != 1)) Error('Vous n\'avez pas l\'autorisation de voir cette page.', 'upload.php');
?>

<!-- Contenu de la page -->

<?php
	// On télécharge les fichiers
	if (isset($_POST['uploadVersion'])) {
		$errorInfo = '';
		$uploadMsg = FormatText(trim($_POST['uploadMsg']));
		$uploadVersion = FormatText(trim($_POST['uploadVersion']));
		if (($uploadMsg == '') or ($uploadVersion == '')) $errorInfo .= 'Vous n\'avez pas renseigné tous les champs<br>';
		if ((empty($_FILES['uploadVbSysLib']['size'])) or (empty($_FILES['uploadVbSysLibDLL']['size']))) {
			$errorInfo .= 'Vous n\'avez pas envoyé tous les fichiers<br>';
		}
		if (VersionExist($uploadVersion)==1) $errorInfo .= 'Ce numéro de version existe déjà<br>';
		// Si il n'y a pas d'erreurs
		if ($errorInfo == '') {
			// On récupère la taille, le nom et le nom des fichiers temporaires
			$fileVSL_size = $_FILES['uploadVbSysLib']['size'];
			$fileVSL_name = $_FILES['uploadVbSysLib']['name'];
			$fileVSL_tmpname = $_FILES['uploadVbSysLib']['tmp_name'];
			$fileVSL_ext = strtolower(substr($fileVSL_name, strrpos($fileVSL_name, '.') + 1));
			$fileDLL_size = $_FILES['uploadVbSysLibDLL']['size'];
			$fileDLL_name = $_FILES['uploadVbSysLibDLL']['name'];
			$fileDLL_tmpname = $_FILES['uploadVbSysLibDLL']['tmp_name'];
			$fileDLL_ext = strtolower(substr($fileDLL_name, strrpos($fileDLL_name, '.') + 1));
			// Vérifie l'extension
			if (!in_array($fileVSL_ext, $uploadExtension)) $errorInfo = $errorInfo . 'Les types de fichiers sont incorrects<br>';
			if (!in_array($fileDLL_ext, $uploadExtension)) $errorInfo = $errorInfo . 'Les types de fichiers sont incorrects<br>';
			// Vérifie la taille des fichiers
			if ($fileVSL_size > $uploadMaxSize) $errorInfo = $errorInfo . 'Les fichiers ont une taille trop importantes<br>';
			if ($fileDLL_size > $uploadMaxSize) $errorInfo = $errorInfo . 'Les fichiers ont une taille trop importantes<br>';
			// Si il n'y a pas d'erreurs
			if ($errorInfo == '') {
				// Déplace les fichiers
				$fileVSL_newname = 'files/VbSysLib_' . $uploadVersion . '.' . $fileVSL_ext;
				$fileDLL_newname = 'files/VbSysLib_DLL_' . $uploadVersion . '.' . $fileVSL_ext;
				move_uploaded_file($fileVSL_tmpname, $fileVSL_newname) or $errorInfo = $errorInfo . 'Impossible d\'enregistrer le fichier, contactez le webmaster<br>';
				move_uploaded_file($fileDLL_tmpname, $fileDLL_newname) or $errorInfo = $errorInfo . 'Impossible d\'enregistrer le fichier, contactez le webmaster<br>';
				// Ajoute à la BDD
				if (@dbConnect() !=0) {
					$date = date('Y-m-d');
					$Requete = "INSERT INTO `versions` (`ID`, `Name`, `Description`, `File`, `FileDLL`, `Date`, `User`) VALUES ('', '$uploadVersion', '$uploadMsg', '$fileVSL_newname', '$fileDLL_newname', '$date', '$userID')";
					$Resultat = mysql_query($Requete);
					dbClose();
				}
				else { $errorInfo .= 'Connexion à la base de données impossible<br>'; }
			}
		}
		$WindowTitle = 'Ajout de la nouvelle version';
		include 'windowtop.php';
		// Affiche les erreurs
		if ($errorInfo != '') {
			echo '<p>Des erreurs sont survenues pendant l\'enregistrement de votre compte :</p>';
			echo "<blockquote class='clair'>$errorInfo</blockquote>";
			echo '<a href="javascript:history.go(-1)">Recommencer</a><br /><br />';
		}
		else {
			?>
				<p>
					La nouvelle version (<?php echo $uploadVersion; ?>) a bien été téléchargée.<br />
					Elle est maintenant disponible au téléchargement.
				</p>
				<p>
					<a href="index.php">Retour à l'accueil</a>
				</p>
			<?php
		}
		include "windowbottom.php";
	}
	else {
		// Mettre à jour
		$WindowTitle = 'Mettre à jour une nouvelle version de la librairie';
		include 'windowtop.php';
			?>
			<p>
				Grace à cette page vous pouvez mettre une nouvelle version de Vb System Library en ligne.
				L'ancienne version sera archivée et la nouvelle sera proposée directement au téléchargement,
				pensez donc à vérifier qu'elle fonctionne.<br /><br />
			</p>
			<form action="upload.php" method="post" enctype="multipart/form-data">
				<p>
					Veuillez donner le numéro de la version :<br />
					Version précédente : <strong><?php echo GetLastVersion(); ?></strong>
				</p>
				<p>
					<input type="text" name="uploadVersion" size="40" maxlength="20">
				</p>
				<p>
					Veuillez placer ici le fichier <strong>ZIP</strong> contenant tous les fichiers (DLL VbSysLib
					et projet d'exemple)
				</p>
				<p>
					<input type="file" name="uploadVbSysLib" size="50">
				</p>
				<p>
					Veuillez placer ici le fichier <strong>ZIP</strong> contenant uniquement la DLL VbSysLib
				</p>
				<p>
					<input type="file" name="uploadVbSysLibDLL" size="50">
				</p>
				<p>
					Maintenant expliquez quelles ont été les mises à jour faites (par exemple listez le nom des
					fonctions ajoutées, le nom des classes crées, ou des méthodes crées...)
				</p>
				<p>
					<textarea name="uploadMsg" rows="10" style="width: 90%"></textarea>
				</p>
				<p>
					<input type="submit" value="Mettre à jour">
				</p>
			</form>
			<?php
		include "windowbottom.php";
	}
?>

<?php include "bottom.php"; ?>
