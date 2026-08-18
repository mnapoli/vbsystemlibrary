<?php
	$PageTitle = 'Liste des codes';
	$xitiPageName = "Liste";
	include 'top.php';
?>
		
	<!-- Liste des codes -->
	<?php
		$WindowTitle = 'Liste des codes';
		include 'windowtop.php';

			// Récupère les paramêtres
			$listTri = '';
			$listIDuser = '';
			$listMin = '';
			$listMax = '';
			$listActifs = '1';
			if (isset($_GET['tri']) and $_GET['tri'] != '') $listTri = $_GET['tri'];
			if (isset($_GET['userid']) and $_GET['userid'] != '') $listIDuser = $_GET['userid'];
			if (isset($_GET['min']) and $_GET['min'] != '') $listMin = $_GET['min'];
			if (isset($_GET['max']) and $_GET['max'] != '') $listMax = $_GET['max'];
			if (isset($_GET['actifs']) and $_GET['actifs'] != '') $listActifs = $_GET['actifs'];
			// Rédaction de la liste des paramêtres
			$listParam = '';
			if ($listIDuser != '') $listParam .= 'Auteur : ' . GetUserName($listIDuser) .' (<a href="list.php?actifs='.$listActifs.'&amp;userid=&amp;tri='.$listTri.'&amp;min='.$listMin.'&amp;max='.$listMax.'">supprimer ce paramêtre</a>)<br />';
			if ($listTri != '') $listParam .= 'Ordre de tri : ' . GetTriName($listTri) .' (<a href="list.php?actifs='.$listActifs.'&amp;userid='.$listIDuser.'&amp;tri=&amp;min='.$listMin.'&amp;max='.$listMax.'">supprimer ce paramêtre</a>)<br />';
			if ($listActifs != '1') $listParam .= 'Afficher les codes désactivés (<a href="list.php?actifs=1&amp;userid='.$listIDuser.'&amp;tri='.$listTri.'&amp;min='.$listMin.'&amp;max='.$listMax.'">supprimer ce paramêtre</a>)<br />';
			
			?>
			
			<p>
				Paramêtres de tri :<br />
				<?php $listTri_tmp = $listTri; ?>
				<?php $listTri = 'b'; ?>
				<a href="list.php?actifs=<?php echo $listActifs; ?>&amp;userid=<?php echo $listIDuser; ?>&amp;tri=<?php echo $listTri; ?>&amp;min=<?php echo $listMin; ?>&amp;max=<?php echo $listMax; ?>">
					Du plus récent au plus ancien
				</a>,
				<?php $listTri = 'c'; ?>
				<a href="list.php?actifs=<?php echo $listActifs; ?>&amp;userid=<?php echo $listIDuser; ?>&amp;tri=<?php echo $listTri; ?>&amp;min=<?php echo $listMin; ?>&amp;max=<?php echo $listMax; ?>">
					Du plus ancien au plus récent
				</a>,
				<?php $listTri = 'a'; ?>
				<a href="list.php?actifs=<?php echo $listActifs; ?>&amp;userid=<?php echo $listIDuser; ?>&amp;tri=<?php echo $listTri; ?>&amp;min=<?php echo $listMin; ?>&amp;max=<?php echo $listMax; ?>">
					Tri alphabétique
				</a>
				<?php $listTri = $listTri_tmp; ?>
			</p>
			
			<?php
			if ($listParam!='') {
				?>
				<p>Paramêtres de la liste :</p>
				<blockquote class="clair">
					<?php
						echo $listParam;
					?>
				</blockquote>
				<?php
			}
			?>
			<br />
			
			<?php
				include 'listcodes.php';
			?>
			<br />
			
			<?php
				// Affiche la liste des pages
				if ($listNBResults > $listcodesMax) {
					$listNBPage = intval($listNBResults / $listcodesMax);
					if (intval($listNBResults / $listcodesMax) != ($listNBResults / $listcodesMax)) {
						$listNBPage = $listNBPage + 1;
					}
					?>
					<div style="text-align: center">
						Pages
						<?php
						for ($t = 1; $t <= $listNBPage; $t++) {
							$listMin_tmp = ($t - 1) * $listcodesMax;
							$listMax_tmp = $t * $listcodesMax;
							?>
								<a href="list.php?actifs=<?php echo $listActifs; ?>&amp;userid=<?php echo $listIDuser; ?>&amp;tri=<?php echo $listTri; ?>&amp;min=<?php echo $listMin_tmp; ?>&amp;max=<?php echo $listMax_tmp; ?>">
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
		include 'windowbottom.php';
	?>

<?php include 'bottom.php'; ?>


