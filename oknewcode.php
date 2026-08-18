<?
	$PageTitle = "Ajout de votre code";
	include "top.php";
?>

<!-- Contenu de la page -->
	<!-- Le code a bien été ajouté -->
	<?
		$WindowTitle = "Ajout de votre code";
		include "windowtop.php";
	?>
		<p>
		<?
			$infos = '';
			// Si on est enregistré
			if ($Logged == "true") {
				// Si on a bien envoyé un code
				if (isset($_POST['title']) and isset($_POST['presentation']) and isset($_POST['code'])) {
					// Il faut récupérer le contenu du formulaire
					$title = FormatText($_POST['title']);
					$presentation = FormatText($_POST['presentation']);
					$code = FormatCode($_POST['code']);
					// Vérifie les données
					if (($title == '') or ($presentation == '') or ($code == '')) {
					   	$infos = $infos . "Vous n'avez pas rempli tous les champs<br />";
					}
					//Si tout est bon (aucune erreur dans $infos)
					if ($infos == ''){
					   	// Enregistre dans Mysql
						$date = date('Y-m-d');
						$time = date('H:i');
						if (@dbConnect() !=0) {
						   	$Requete = "INSERT INTO `codes` ( `ID` , `IDuser` , `title` , `code` , `date` , `time` , `Presentation`) VALUES ('', '$userID', '$title', '$code', '$date', '$time', '$presentation')"; 
							$Resultat = mysql_query($Requete);
						}
						else {
					   		$infos = $infos . "Impossible de se connecter à la base de données<br />";
						}
						// Et pour mettre fin à la connexion
						dbClose();
					}
				}
				else $infos = $infos . "Aucun code n'a été proposé à ajouter<br>";
			}
			else $infos = $infos . "Vous n'êtes pas enregistré<br>";
			
			// Affiche les erreurs
			if ($infos != '') {
				echo "Des erreurs sont survenues pendant l'enregistrement du code :<br>";
				echo "<blockquote class='clair'>$infos</blockquote><br /><br />";
			}
			else {
				echo "Votre code a bien été ajouté<br />
					Merci de votre participation.<br /><br />";
			}
		?>
		<a href="index.php">Retour à la page d'accueil</a><br><br>
	<?
		include "windowbottom.php";
	?>

<?php include "bottom.php"; ?>
