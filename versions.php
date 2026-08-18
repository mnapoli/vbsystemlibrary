<?php
	$PageTitle = 'Liste des versions de Vb System Library';
	$xitiPageName = "Versions";
	include 'top.php';
?>
		
	<!-- Liste des versions -->
	<?php
		$WindowTitle = 'Liste des versions de Vb System Library';
		include 'windowtop.php';
		
			?>
				<p>
					Cette page contient la liste de toutes les anciennes versions de Vb System Library.<br />
					Vous pouvez consulter les fichiers à volonté en utilisant les liens pour les télécharger.
				</p>
			<?php

			// Récupère les paramêtres
			$listMin = 0;
			$listMax = $listversionsMax;
			if (isset($_GET['min']) and $_GET['min'] != '') $listMin = $_GET['min'];
			if (isset($_GET['max']) and $_GET['max'] != '') $listMax = $_GET['max'];
			?>
			<br />
			
			<?php
				include 'listversions.php';
			?>
			<br />
			
			<?php
				// Affiche la liste des pages
				if ($listNBResults > $listversionsMax) {
					$listNBPage = intval($listNBResults / $listversionsMax);
					if (intval($listNBResults / $listversionsMax) != ($listNBResults / $listversionsMax)) {
						$listNBPage = $listNBPage + 1;
					}
					?>
					<div style="text-align: center">
						Pages
						<?php
						for ($t = 1; $t <= $listNBPage; $t++) {
							$listMin_tmp = ($t - 1) * $listversionsMax;
							$listMax_tmp = $t * $listversionsMax;
							?>
								<a href="versions.php?min=<?php echo $listMin_tmp; ?>&amp;max=<?php echo $listMax_tmp; ?>">
									<?php
										if ($listMin_tmp == $listMin) echo '<b>';
										echo $t;
										if ($listMin_tmp == $listMin) echo '</b>';
									?></a>
							<?php
						}
						?>
					</div>
					<?php
				}
			?>
			<br />
			
	<?php
		include 'windowbottom.php';
	?>

<?php include 'bottom.php'; ?>
