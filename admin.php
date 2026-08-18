<?
	$PageTitle = "Page d'administration";
	$xitiPageName = "Administration";
	include 'top.php';
	
	// Vérifie l'identité de l'utilisateur
	if (($Logged=='false') or ($userRights != 1)) Error('Vous n\'avez pas l\'autorisation de voir cette page.', 'admin.php');
	
	// Ajoute la news
	if (isset($_POST['newsTitle'])) {
		// Récupère la news
		$newsTitle = FormatText($_POST['newsTitle']);
		$newsText = FormatText($_POST['newsText']);
		$tempIsCo = $IsConnected;
		if ((dbConnect() != 0) and ($newsTitle != '') and ($newsText != '')) {
			// Enregistre la news
			$date = date('Y-m-d');
			$req = "INSERT INTO `news` (`ID`, `Title`, `Text`, `Date`, `IDauteur`) VALUES ('', '$newsTitle', '$newsText', '$date', '$userID')";
			$result = mysql_query($req);
			// Affiche si l'opération a réussi
			$WindowTitle = 'Ajouter une News';
			include 'windowtop.php';
			if ($result) {
				echo '<p>Enregistrement réussi.</p>';
			}
			else {
				echo '<p>Il y a eu une erreur pendant l\'enregistrement.</p>';
			}
			include 'windowbottom.php';
			if ($tempIsCo == 0) { dbClose(); }
		}
	}
	
	// Page normale
	else {
		?>
		
			<?
				$WindowTitle = 'Ajouter une News';
				include 'windowtop.php';
			?>
				<p style="text-align: center; font-weight: bold; color: #FF0000">/!\ Page réservée aux administrateurs /!\</p>
			<?
				include 'windowbottom.php';
			?>
		
			<?
				$WindowTitle = 'Ajouter une News';
				include 'windowtop.php';
			?>
				<p>Titre de l'article :</p>
				<form name="news" action="admin.php" method="post">
					<input type="text" name="newsTitle" maxlength="100" style="width: 100%">
					<p>Article :</p>
					<textarea name="newsText" rows="15" style="width: 100%"></textarea><br /><br />
					<div style="text-align: right"><input type="submit" value="Ajouter"></div>
				</form>
			<?
				include 'windowbottom.php';
			?>
		
			<?
				$WindowTitle = 'Liste des codes désactivés';
				include 'windowtop.php';
			?>
				<p style="text-align: center"><a href="list.php?actifs=0">Voir la liste des codes désactivés</a></p>
			<?
				include 'windowbottom.php';
			?>
		
			<?
				If ($userID=='1') {
					$WindowTitle = 'Voir le journal';
					include 'windowtop.php';
					?>
						<p style="text-align: center"><a href="log.php">Journal</a></p>
					<?
					include 'windowbottom.php';
				}
			?>
		
		<?php
	}

include 'bottom.php';
?>
