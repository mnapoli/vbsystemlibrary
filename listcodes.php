<?php
	// On formate les variables si elles n'existent pas
	if ((!isset($listTri)) or (@$listTri=='')) $listTri = '';
	if ((!isset($listIDuser)) or (@$listIDuser=='')) $listIDuser = '';
	if ((!isset($listMin)) or (@$listMin=='')) $listMin = 0;
	if ((!isset($listMax)) or (@$listMax=='')) $listMax = $listcodesMax;
	if ((!isset($listActifs)) or (@$listActifs=='')) $listActifs = '1';
	// On formate la requete
		$reqCond = 'WHERE ';
		// Codes activés ou non
		$reqCond .= " `Actif` = '$listActifs'";
		// Auteur
		if ($listIDuser != '') { $reqCond .= " AND `IDuser` = '$listIDuser'"; }
		// Ordre
		if ($listTri == '') { $reqTri = 'ORDER BY `ID` DESC'; } // Date décroissant
		if ($listTri == 'a') { $reqTri = 'ORDER BY `title` ASC'; } // Alphabétique croissant
		if ($listTri == 'b') { $reqTri = 'ORDER BY `ID` DESC'; } // Date décroissant
		if ($listTri == 'c') { $reqTri = 'ORDER BY `ID` ASC'; } // Date croissant
		// Limites
		$reqLim = 'LIMIT ' . $listMin . ', ' . ($listMax - $listMin);
	// Affiche les sous-catégories
	if (@dbConnect() != 0) {
		// recherche le nombre de résultats
		$req = 'SELECT COUNT(*) FROM `codes` ' . $reqCond;
		$result = mysql_query($req);
		$listNBResults = 0;
		if ($result) {
			$rs = mysql_fetch_row($result);
			$listNBResults = $rs[0];
		}
		$req = 'SELECT `ID`, `title`, `date`, `IDuser` FROM `codes` ' . $reqCond . ' ' . $reqTri . ' ' . $reqLim;
		$result = mysql_query($req);
		// Le titre du tableau
		?>
		<div align="center">
		<table class="codeTable" cellspacing="2">
			<tr>
				<th class="codeTitle" align="center">
					Date<br>
				</th>
				<th class="codeTitle" align="center" style="width: 100%">
					Titre<br>
				</th>
				<th class="codeTitle" align="center">
					Auteur<br>
				</th>
			</tr>
		<?php
		if (mysql_num_rows($result) == 0) {
				echo '<td></td><td class="codeText">Aucun Code</td>';
		}
		while ($rs = mysql_fetch_row($result)) {
			// Trouve l'auteur principal
			$codeID = $rs[0];
			$codeTitle = $rs[1];
			$codeDate = FormatDate($rs[2]);
			$codeIDuser = $rs[3];
			// Affiche le tutoriel
			?>
				<tr>
					<!-- Date -->
					<td class="codeText" style="font-size: 10px" nowrap>
						<?php echo $codeDate; ?><br />
					</td>
					<!-- Titre -->
					<td class="codeText">
						<b><a href="code.php?ID=<?php echo $codeID; ?>"><?php echo $codeTitle; ?></a></b><br />
					</td>
					<!-- Auteur -->
					<td class="codeText" nowrap>
						<a href="user.php?ID=<?php echo $codeIDuser; ?>">
						   	<?php echo GetUserName($codeIDuser); ?><br />
						</a>
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
?>
