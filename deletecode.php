<?
	$PageTitle = "Désactiver un code";
	include "top.php";
	// Récupère le numéro du code
	@$codeID = $_GET['ID'] or die ("aucun code défini");
	// Récupère les infos associées
	if (@dbConnect() !=0) {
		$req = "SELECT `ID`, `IDuser`, `title`, `presentation`, `code`, `Historique` FROM `codes` WHERE `ID` = '$codeID' AND `Actif` = '1'"; 
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult!=0) {
			$rs = mysql_fetch_row($result);
				$codeIDuser = $rs[1];
				$codeTitle = InverseNl2br($rs[2]);
				$codePresentation = InverseNl2br($rs[3]);
				$codeCode = InverseNl2br($rs[4]);
				$codeHistorique = $rs[5];
			dbClose();
		}
		else {
			die("Ce code n'existe plus.");
		}
	}
	else {
		die('Impossible de se connecter à la base.');
	}
	
	// Action validée
	if (isset($_POST['deleteOk'])) {
		if ($_POST['deleteOk']=='True') {
			// Vérifie qu'on ai bien les droits
			if (($Logged=='true') and ($userRights == '1')) {
				if (dbConnect() != 0) {
					// Supprime le code
					$req = "UPDATE `codes` SET `Actif` = '0' WHERE `ID` = '$codeID'";
					$result = mysql_query($req);
					dbClose();
				}
			}
			Redirect('index.php');
		}
	}
?>

<!-- Contenu de la page -->
	<!-- Désactiver un code -->
	<?
		$WindowTitle = "Désactiver le code";
		include "windowtop.php";
		if (($Logged=="false") or ($userRights != '1')) {
			?>
				<p>Vous n'êtes pas autorisés à désactiver ce code.</p>
			<?
		}
		else {
			?>
			<p>
				<p>
					<b>Voulez-vous vraiment désactiver le code n°<?php echo $codeID; ?> ?</b>
				</p>
				<form action="deletecode.php?ID=<?php echo $codeID; ?>" style="text-align: center" method="post">
					<div style="margin-left: 10%;">
						<input type="hidden" name="deleteOk" value="True">
						<input type="submit" value="Oui">
						<input type="button" value="Annuler" onclick='window.location="code.php?ID=<?php echo $codeID; ?>"'>
					</div><br />
							
				</form>
			</p>
			<?php
		}
		include "windowbottom.php";
	?>

<?php include "bottom.php"; ?>
