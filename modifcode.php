<?
	$PageTitle = "Modifier un code";
	$xitiPageName = "ModifierCode";
	include "top.php";
	// Récupère le numéro du code
	@$codeID = $_GET['ID'] or die ("aucun code défini");
	// Récupère les infos associées
	if (@dbConnect() !=0) {
		$req = "SELECT `ID`, `IDuser`, `title`, `presentation`, `code`, `Historique` FROM `codes` WHERE `ID` = '$codeID'"; 
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
?>

<!-- Contenu de la page -->
	<!-- Ajouter un code -->
	<?
		$WindowTitle = "Modifier un code";
		include "windowtop.php";
		if (($Logged=="false") or (($userRights != '1') and ($userID != $codeIDuser))) {
			?>
				<p>Vous n'êtes pas autorisés à modifier ce code.</p>
			<?
		}
		else {
			?>
			<p>
				<form action="okmodif.php?ID=<?php echo $codeID; ?>" method="post">
							
					<b>Explications de la modification :</b><br />
					<div style="margin-left: 10%;">
						<textarea class="Clair" name="modifExplications" rows="4" style="width: 100%"></textarea><br />
					</div><br />
					
					<b>Titre du code :</b><br />
					<div style="margin-left: 10%;">
						<input class="Clair" type="text" name="modifTitle" maxlength="256" style="width: 100%" value="<?php echo $codeTitle; ?>"><br />
					</div><br />
					
					<b>Présentation du code :</b><br />
					<div style="margin-left: 10%;">
						<textarea class="Clair" name="modifPresentation" rows="8" style="width: 100%"><?php echo $codePresentation; ?></textarea><br />
						<font size="1">Précisez ici quelle partie de la librairie est concernée, s'il s'agit de
						modifier ou d'ajouter une fonction etc...</font>
					</div><br />
							
					<b>Le code source :</b><br />
					<div style="margin-left: 10%;">
						<textarea class="Clair" name="modifCode" rows="20" style="width: 100%"><?php echo $codeCode; ?></textarea><br />
					</div><br />
						
					<div style="text-align: center">
						Cliquez ici pour modifier le code :<br /><br />
						<input type="submit" value="Modifier">
					</div>
							
				</form>
			</p>
			<?php
		}
		include "windowbottom.php";
	?>

<?php include "bottom.php"; ?>
