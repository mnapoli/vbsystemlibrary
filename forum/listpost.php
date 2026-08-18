<!-- Derniers messages -->
<?
	$WindowTitle = "Derniers Messages du forum";
	if (isset($postIDCateg) and $postIDCateg!='') $WindowTitle = 'Derniers messages dans la catégorie ' . GetCatName($postIDCateg);
	include "windowtop.php";
	?>
		<br />
		<!-- Liens de retour -->
		<?php
			if (isset($postIDCateg)) {
				?>
				<a href="forum.php">&lt;&lt; Retour au sommaire du forum</a><br /><br />
				<?php
			}
		?>
		<?php
		// On formate les variables si elles n'existent pas
		if (!isset($postIDCateg) or $postIDCateg=='') $postIDCateg = '';
		if (!isset($postTri) or $postTri=='') $postTri = 'a';
		if (!isset($postMin) or $postMin=='') $postMin = 0;
		if (!isset($postMax) or $postMax=='') $postMax = $listpostMax;
		// On formate la requete
			$reqCond = "WHERE `IDParent` = '0' AND `Actif` = '1'";
			// Catégorie
			if ($postIDCateg != '') { $reqCond .= " AND `IDCateg` = '$postIDCateg'"; }
			// Ordre
			if ($postTri == 'a') { $reqTri = 'ORDER BY `ID` DESC'; } // Date décroissant
			if ($postTri == 'b') { $reqTri = 'ORDER BY `ID` ASC'; } // Date croissant
			// Limites
			$reqLim = 'LIMIT ' . $postMin . ', ' . ($postMax - $postMin);
		// Affiche les sous-catégories
		if (@dbConnect() != 0) {
			// Le titre du tableau
			?>
			<div align="center">
			<table class="forumPost" cellspacing="2">
				<tr>
					<th class="forumPost" align="center" style="width: 100%">
						Titre<br>
					</th>
					<th class="forumPost" align="center" style="white-space: nowrap">
						Réponses<br>
					</th>
					<th class="forumPost" align="center" style="white-space: nowrap">
						Catégorie<br>
					</th>
					<th class="forumPost" align="center" style="white-space: nowrap">
						Posté par<br>
					</th>
				</tr>
			<?php
			// recherche le nombre de résultats
			$req = 'SELECT COUNT(*) FROM `forum` ' . $reqCond;
			$result = mysql_query($req);
			$postNBResults = 0;
			if ($result) {
				$rs = mysql_fetch_row($result);
				$postNBResults = $rs[0];
			}
			$req = 'SELECT `ID`, `IDUser`, `Title`, `IDCateg`, `IP` FROM `forum` ' . $reqCond . ' ' . $reqTri . ' ' . $reqLim; 
			$result = mysql_query($req);
			$CategNBTuto = 0;
			if (mysql_num_rows($result) == 0) {
					echo '<td class="tutoText">Aucun message</td>';
			}
			while ($rs = mysql_fetch_row($result)) {
				// Trouve l'auteur principal
				$postID_tmp = $rs[0];
				$postIDUser_tmp = $rs[1];
				$postTitle_tmp = $rs[2];
				$postIDCateg_tmp = $rs[3];
				$postIP_tmp = $rs[4];
				list($userName_tmp) = GetUserInfos($postIDUser_tmp);
				// Nombre de réponses
				$NBReplies_tmp = GetNBReplies($postID_tmp);
				// Affiche le tutoriel
				?>
					<tr>
						<!-- Titre -->
						<td class="forumPost" style="text-indent: 15px">
							<b><a href="forum.php?ID=<?php echo $postID_tmp; ?>">
								<?php echo $postTitle_tmp; ?></a>
							</b><br />
						</td>
						<!-- Nombre de réponses -->
						<td class="forumPost" style="white-space: nowrap; text-align: center">
							<?php echo $NBReplies_tmp; ?><br />
						</td>
						<!-- Catégorie -->
						<td class="forumPost" style="white-space: nowrap; text-align: center">
							<b><a href="forum.php?categ=<?php echo $postIDCateg_tmp; ?>"><?php echo GetCatName($postIDCateg_tmp); ?></a></b><br />
						</td>
						<!-- Auteur -->
						<td class="forumPost" style="white-space: nowrap; text-align: center">
							<b><a href="user.php?ID=<?php echo $postIDUser_tmp; ?>"><?php echo $userName_tmp; ?></a></b><br />
						</td>
					</tr>
				<?php
			}
			?>
				</table>
				</div>
			<?php
			dbClose();
		}
		else {
			echo "Impossible d'afficher la liste des catégories<br>";
		}
		
		// Affiche la liste des pages
		if (isset($postList)) {		// Si on est dans un mode 'liste'
			if ($postNBResults > $listpostMax) {
				$postNBPage = intval($postNBResults / $listpostMax);
				if (intval($postNBResults / $listpostMax) != ($postNBResults / $listpostMax)) {
					$postNBPage = $postNBPage + 1;
				}
				?>
				<div style="text-align: center">
					Pages
					<?php
					for ($t = 1; $t <= $postNBPage; $t++) {
						$postMin_tmp = ($t - 1) * $listpostMax;
						$postMax_tmp = $t * $listpostMax;
						?>
							<a href="forum.php?list&amp;categ=<?php echo $postIDCateg; ?>&amp;tri=<?php echo $postTri; ?>&amp;min=<?php echo $postMin_tmp; ?>&amp;max=<?php echo $postMax_tmp; ?>">
								<?php
									if ($postMin_tmp == $postMin) echo '<b>';
									echo $t;
									if ($postMin_tmp == $postMin) echo '</b>';
								?>
							</a>
						<?php
					}
					?>
				</div>
				<?php
			}
		}
		
		if (!isset($postList)) {
			$endUrl = '';
			if ($postIDCateg != '') $endUrl = '&amp;categ=' . $postIDCateg;
			?>
			<br />
			<div style="text-align: right">
				<?php
					$fin = '';
					if (isset($postIDCateg)) $fin = '&amp;categ=' . $postIDCateg;
				?>
				<a href="forum.php?ajout<?php echo $fin; ?>">Poster un nouveau message</a>
			</div>
			<div style="text-align: right">
				<a href="forum.php?list<?php echo $endUrl; ?>">Voir la suite</a>
			</div>
			<?php
		}
		?>
		<br />
		<?php
	include "windowbottom.php";
?>

