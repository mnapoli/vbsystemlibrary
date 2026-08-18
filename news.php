<?php
	$PageTitle = "Dernières News";
	$xitiPageName = "News";
	include "top.php";
?>

	<?php
		$WindowTitle = "News";
		include "windowtop.php";
		?>
		<br />
		<?php
		// Liste les news
		if (@dbConnect() !=0) {
			// Récupère les paramêtres
			$listMin = 0;
			$listMax = $listnewsMax;
			if (isset($_GET['min']) and $_GET['min'] != '') $listMin = $_GET['min'];
			if (isset($_GET['max']) and $_GET['max'] != '') $listMax = $_GET['max'];
			// recherche le nombre de résultats
			$req = 'SELECT COUNT(*) FROM `news`';
			$result = mysql_query($req);
			$NBResults = 0;
			if ($result) {
				$rs = mysql_fetch_row($result);
				$NBResults = $rs[0];
			}
			// Limites
			$reqLim = 'LIMIT ' . $listMin . ', ' . ($listMax - $listMin);
			$req = "SELECT `ID`, `Title`, `Text`, `Date`, `IDauteur` FROM `news` ORDER BY `ID` DESC " . $reqLim;
			$result = mysql_query($req);
			if ($NBResults == 0) {
				// Pas de résultats
				?>
					<p>Aucune news pour le moment</p>
				<?php
			}
			while ($rs = mysql_fetch_row($result)) {
				$newsID_tmp = $rs[0];
				$newsTitle_tmp = $rs[1];
				$newsText_tmp = $rs[2];
				$newsDate_tmp = FormatDate($rs[3]);
				$newsIDuser_tmp = $rs[4];
				$newsUserName_tmp = GetUserName($newsIDuser_tmp);
				// Affiche la news
				$WindowTitle = $newsTitle_tmp;
				include "windowtop.php";
					?>
					<h3 id="news<?php echo $newsID_tmp; ?>">
						<img src="images/news.gif" border="0" width="32" height="32" alt="" align="middle">
						<?php echo $newsTitle_tmp; ?>
					</h3>
					<span class="News_Date">
						 Posté le <?php echo $newsDate_tmp; ?>
						 par <a href="user.php?ID=<?php echo $newsIDuser_tmp; ?>">
						 <?php echo $newsUserName_tmp; ?>
						 </a>
					</span>
					<hr width="100%" size="1" style="color: #7375d3; background-color: #7375d3; border-color: #7375d3;">
					<p class="News">
						<?php echo $newsText_tmp; ?>
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
				if ($NBResults > $listnewsMax) {
					$listNBPage = intval($NBResults / $listnewsMax);
					if (intval($NBResults / $listnewsMax) != ($NBResults / $listnewsMax)) {
						$listNBPage = $listNBPage + 1;
					}
					?>
					<div style="text-align: center">
						Pages
						<?php
						for ($t = 1; $t <= $listNBPage; $t++) {
							$listMin_tmp = ($t - 1) * $listnewsMax;
							$listMax_tmp = $t * $listnewsMax;
							?>
								<a href="news.php?min=<?php echo $listMin_tmp; ?>&amp;max=<?php echo $listMax_tmp; ?>">
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


<?php include "bottom.php"; ?>
