<?
	$PageTitle = "Modification du code";
	include "top.php";
	// Récupère le numéro du code
	@$codeID = $_GET['ID'] or die ("aucun code défini");
	// Récupère les infos associées
	if (@dbConnect() !=0) {
		$req = "SELECT `IDuser`, `Historique` FROM `codes` WHERE `ID` = '$codeID'"; 
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult!=0) {
			$rs = mysql_fetch_row($result);
				$codeIDuser = $rs[0];
				$codeHistorique = $rs[1];
			dbClose();
		}
		else {
			die("Ce code n'existe plus.");
		}
	}
	else {
		die('Impossible de se connecter à la base.');
	}
?>

<!-- Contenu de la page -->
	<!-- Le code a bien été modifié -->
	<?
		$WindowTitle = "Modification du code";
		include "windowtop.php";
	?>
		<p>
		<?
			$infos = '';
			// Si on a le droit de modifier
			if (($Logged == "true") and (($userRights == '1') or ($userID == $codeIDuser))) {
				// Si on a bien envoyé un code
				if (isset($_POST['modifTitle']) and isset($_POST['modifPresentation']) and isset($_POST['modifCode']) and isset($_POST['modifExplications'])) {
					// Il faut récupérer le contenu du formulaire
					$modifTitle = FormatText($_POST['modifTitle']);
					$modifPresentation = FormatText($_POST['modifPresentation']);
					$modifCode = FormatCode($_POST['modifCode']);
					$modifExplications = FormatText($_POST['modifExplications']);
					// Petit bug : on enlève tous les guillemets simples des explications
					$modifExplications = str_replace("'", '', $modifExplications);
					// Vérifie les données
					if (($modifTitle == '') or ($modifPresentation == '') or ($modifCode == '') or ($modifExplications == '')) {
					   	$infos = $infos . "Vous n'avez pas rempli tous les champs<br />";
					}
					//Si tout est bon (aucune erreur dans $infos)
					if ($infos == ''){
					   	// Enregistre dans Mysql
						$date = date('Y-m-d');
						$time = date('H:i');
						if (@dbConnect() !=0) {
							$Requete = "UPDATE `codes` SET `title` = '$modifTitle', `Presentation` = '$modifPresentation', `code` = '$modifCode' WHERE `ID` = '$codeID'";
							$Resultat = mysql_query($Requete);
							AddHistorique($codeID, $modifExplications, $userName);
						}
						else {
					   		$infos = $infos . "Impossible de se connecter à la base de données<br />";
						}
						// Et pour mettre fin à la connexion
						dbClose();
					}
				}
				else $infos = $infos . "Aucun code n'a été proposé à modifier<br>";
			}
			else $infos = $infos . "Vous n'êtes pas enregistré<br>";
			
			// Affiche les erreurs
			if ($infos != '') {
				echo "Des erreurs sont survenues pendant la modification du code :<br>";
				echo "<blockquote class='clair'>$infos</blockquote><br /><br />";
			}
			else {
				echo "Votre code a bien été modifié<br />
					Merci de votre participation.<br /><br />";
			}
		?>
		<a href="code.php?ID=<?php echo $codeID; ?>">Retour au code</a><br><br>
		<a href="index.php">Retour à la page d'accueil</a><br><br>
	<?
		include "windowbottom.php";
	?>

<?php include "bottom.php"; ?>
