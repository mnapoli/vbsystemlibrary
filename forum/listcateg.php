<!-- Catégories -->
<?php
	$WindowTitle = "Liste des catégories du forum";
	include "windowtop.php";
		?>
			<p style="text-align: center">
				<a href="forum.php?ajout">Poster un nouveau message</a>
			</p>
			<div align="center">
			<table cellspacing="4" style="text-align: left; width: 90%">
				<?php
					// Affiche les catégories
					if (@dbConnect() !=0) {
						$req = "SELECT `ID`, `name`, `image` FROM `forumcateg` ORDER BY `ID` ASC"; 
						$result = mysql_query($req);
						$CategNBTuto = 0;
						if (mysql_num_rows($result) == 0) {
								echo 'Aucun langage';
						}
						while ($rs = mysql_fetch_row($result)) {
							// Récupère le nombre de tutoriels dans la catégorie
							$req = "SELECT COUNT(*) FROM `forum` WHERE `IDCateg`='" . $rs[0] . "' AND `IDParent`='0' AND `Actif` = '1'"; 
							$result2 = mysql_query($req);
							$rs2 = mysql_fetch_row($result2);
							$CategNBPost = $rs2[0];
							$categID = $rs[0];
							$categName = $rs[1];
							$categImage = $rs[2];
							?>
								<tr>
									<td style="width: 32px; text-align: center">
										<?php
											if ($categImage) {
												echo '<img src="' . $categImage . '" alt="" style="border-width: 0px">';
											}
										?>
									</td>
									<td>
										<b><a href="forum.php?categ=<?php echo $categID; ?>"><?php echo $categName; ?></a></b><br>
										<span style="font-style: italic; font-size: 10px; color: #808080">
											<?php echo $CategNBPost; ?> Messages
										</span>
									</td>
								</tr>
							<?php
						}
						dbClose();
					}
				?>
			</table>
			</div>
			<br />
		<?php
	include "windowbottom.php";
?>
