<?php
	// On formate les variables si elles n'existent pas
	if ((!isset($listMin)) or (@$listMin=='')) $listMin = 0;
	if ((!isset($listMax)) or (@$listMax=='')) $listMax = $listversionsMax;
	// On formate la requete
		// Limites
		$reqLim = 'LIMIT ' . $listMin . ', ' . ($listMax - $listMin);
	if (@dbConnect() != 0) {
		// recherche le nombre de résultats
		$req = 'SELECT COUNT(*) FROM `versions`';
		$result = mysql_query($req);
		$listNBResults = 0;
		if ($result) {
			$rs = mysql_fetch_row($result);
			$listNBResults = $rs[0];
		}
		$req = 'SELECT `ID`, `Name`, `Description`, `File`, `FileDLL`, `Date`, `User`, `Clics` FROM `versions` ORDER BY ID DESC ' . $reqLim;
		$result = mysql_query($req);
		// Le titre du tableau
		?>
		<div align="center">
		<table class="updatesTable" cellspacing="2">
			<tr>
				<th class="updatesTitle" align="center">
					Version<br>
				</th>
				<th class="updatesTitle" align="center" style="width: 100%">
					Description de la version<br>
				</th>
				<th class="updatesTitle" align="center">
					Date de sortie<br>
				</th>
				<th class="updatesTitle" align="center">
					Projet Complet<br>
				</th>
				<th class="updatesTitle" align="center">
					DLL uniquement<br>
				</th>
				<th class="updatesTitle" align="center">
					Utilisateur responsable<br>
				</th>
				<th class="updatesTitle" align="center">
					<span style="font-size: 11px;">Téléchargements<br></span>
				</th>
			</tr>
		<?php
		if (mysql_num_rows($result) == 0) {
				echo '<td></td><td class="updatesText">Aucune Mise à jour</td>';
		}
		while ($rs = mysql_fetch_row($result)) {
			// Trouve l'auteur principal
			$updateID = $rs[0];
			$updateVersion = $rs[1];
			$updateDescription = $rs[2];
			$updateFile = $rs[3];
			$updateFileDLL = $rs[4];
			$updateDate = FormatDate($rs[5]);
			$updateIDuser = $rs[6];
			$updateClics = $rs[7];
			// Affiche le tutoriel
			?>
				<tr>
					<td class="updatesText" style="text-align: center; text-indent: 0">
						<b><?php echo $updateVersion; ?></b><br />
					</td>
					<td class="updatesText" style="font-size: 10px">
						<?php echo $updateDescription; ?><br />
					</td>
					<td class="updatesText" style="text-align: center; text-indent: 0">
						<?php echo $updateDate; ?><br />
					</td>
					<td class="updatesText" style="text-align: center; text-indent: 0">
						<b><a href="dlversion.php?id=<?php echo $updateID; ?>&amp;dll=2">Télécharger</a></b><br />
					</td>
					<td class="updatesText" style="text-align: center; text-indent: 0">
						<b><a href="dlversion.php?id=<?php echo $updateID; ?>&amp;dll=1">Télécharger</a></b><br />
					</td>
					<td class="updatesText" style="text-align: center; text-indent: 0">
						<a href="user.php?ID=<?php echo $updateIDuser; ?>">
						   	<?php echo GetUserName($updateIDuser); ?><br />
						</a>
					</td>
					<td class="updatesText" style="text-align: center; text-indent: 0">
						<?php echo $updateClics; ?><br />
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
