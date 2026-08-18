<?php
	$PageTitle = "Livre d'or";
	$xitiPageName = "Livre Or";
	include "top.php";
	
	// Ajoute la news
	if (isset($_POST['messAuteur'])) {
		// Récupère la news
		$messAuteur = FormatText($_POST['messAuteur']);
		$messText = FormatText($_POST['messText']);
		$messMail = FormatText($_POST['messMail']);
		$tempIsCo = $IsConnected;
		if ((dbConnect() != 0) and ($messAuteur != '') and ($messText != '')) {
			// Enregistre la news
			$date = date('Y-m-d');
			$req = "INSERT INTO `livreor` (`ID`, `Auteur`, `Text`, `Date`, `Mail`) VALUES ('', '$messAuteur', '$messText', '$date', '$messMail')";
			$result = mysql_query($req);
			if ($tempIsCo == 0) { dbClose(); }
		}
	}
?>

	<?php
		$WindowTitle = "Messages postés dans le livre d'or";
		include "windowtop.php";
		?>
		<br />
		<?php
		// Liste les news
		if (@dbConnect() !=0) {
			// Récupère les paramêtres
			$listMin = 0;
			$listMax = $listlivreorMax;
			if (isset($_GET['min']) and $_GET['min'] != '') $listMin = $_GET['min'];
			if (isset($_GET['max']) and $_GET['max'] != '') $listMax = $_GET['max'];
			// recherche le nombre de résultats
			$req = 'SELECT COUNT(*) FROM `livreor`';
			$result = mysql_query($req);
			$NBResults = 0;
			if ($result) {
				$rs = mysql_fetch_row($result);
				$NBResults = $rs[0];
			}
			// Limites
			$reqLim = 'LIMIT ' . $listMin . ', ' . ($listMax - $listMin);
			$req = "SELECT `ID`, `Auteur`, `Text`, `Date`, `Mail` FROM `livreor` WHERE `Actif`='1' ORDER BY `ID` DESC " . $reqLim;
			$result = mysql_query($req);
			if ($NBResults == 0) {
				// Pas de résultats
				?>
					<p>Aucun message pour le moment</p>
				<?php
			}
			while ($rs = mysql_fetch_row($result)) {
				$messID_tmp = $rs[0];
				$messAuteur_tmp = $rs[1];
				$messText_tmp = $rs[2];
				$messDate_tmp = FormatDate($rs[3]);
				$messMail_tmp = $rs[4];
				// Affiche la news
				$WindowTitle = 'Message de ';
				if ($messMail_tmp <> '') $WindowTitle = $WindowTitle . '<a href="mailto:' . $messMail_tmp . '">';
				$WindowTitle = $WindowTitle . $messAuteur_tmp;
				if ($messMail_tmp <> '') $WindowTitle = $WindowTitle . '</a>';
				$WindowTitle = $WindowTitle . ' posté le ' . $messDate_tmp;
				include "windowtop.php";
					?>
					<p class="News">
						<?php echo $messText_tmp; ?>
					</p>
					<br />
					<?
				include "windowbottom.php";
			}
			dbClose();
		}
		?>
			
			<?php
				// Affiche la liste des pages
				if ($NBResults > $listlivreorMax) {
					$listNBPage = intval($NBResults / $listlivreorMax);
					if (intval($NBResults / $listlivreorMax) != ($NBResults / $listlivreorMax)) {
						$listNBPage = $listNBPage + 1;
					}
					?>
					<div style="text-align: center">
						Pages
						<?php
						for ($t = 1; $t <= $listNBPage; $t++) {
							$listMin_tmp = ($t - 1) * $listlivreorMax;
							$listMax_tmp = $t * $listlivreorMax;
							?>
								<a href="livredor.php?min=<?php echo $listMin_tmp; ?>&amp;max=<?php echo $listMax_tmp; ?>">
									<?php
										if ($listMin_tmp == $listMin) echo '<b>';
										echo $t;
										if ($listMin_tmp == $listMin) echo '</b>';
									?>
								</a>
							<?php
						}
						?>
					</div>
					<?php
				}
			?>
			<br />
			
			<?php
		include "windowbottom.php";
	?>

	<?
		$WindowTitle = 'Ajouter un message dans le livre d\'or';
		include 'windowtop.php';
	?>
		<form name="news" action="livredor.php" method="post">
			<p>Votre nom (obligatoire) :</p>
			<input type="text" name="messAuteur" maxlength="100" style="width: 100%">
			<p>Votre mail (facultatif) :</p>
			<input type="text" name="messMail" maxlength="100" style="width: 100%">
			<p>Texte :</p>
			<textarea name="messText" rows="15" style="width: 100%"></textarea><br /><br />
			<div style="text-align: right"><input type="submit" value="Ajouter"></div>
		</form>
	<?
		include 'windowbottom.php';
	?>


<?php include "bottom.php"; ?>