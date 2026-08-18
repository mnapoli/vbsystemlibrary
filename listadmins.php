<?
	$PageTitle = "Liste des admins";
	$xitiPageName = "ListeAdmins";
	include "top.php";
?>

<!-- Contenu de la page -->

	<!-- Pour les débutants -->
	<?
		$WindowTitle = "Liste des admins";
		include "windowtop.php";
		?>
			<div align="center"><br />
			<table cellspacing="4" class="ListeMembres">
				<tr>
					<th class="ListeMembres" style="width: 5%">
						ID
					</th>
					<th class="ListeMembres" style="width: 10%">
						Image
					</th>
					<th class="ListeMembres" style="width: 45%">
						Pseudo
					</th>
					<th class="ListeMembres" style="width: 20%">
						Mail
					</th>
					<th class="ListeMembres" style="width: 20%">
						Site Perso
					</th>
				</tr>
				<?php
					// Cherche la liste
					if (@dbConnect() !=0) {
						$req = "SELECT `ID`, `Name`, `PublicMail`, `Avatar`, `Website` FROM `users` WHERE `Actif` = '1' AND `Rights` = '1' ORDER BY `ID` ASC";
						$result = mysql_query($req);
						if (mysql_num_rows($result) == 0) {
								echo 'Aucune entrée';
						}
						while ($rs = mysql_fetch_row($result)) {
							$userID_tmp = $rs[0];
							$userName_tmp = $rs[1];
							$userMail_tmp = $rs[2];
							$userAvatar_tmp = $rs[3];
							$userWebsite_tmp = $rs[4];
							?>
							<tr>
								<td style="text-align: center">
									<?php
										// ID
										echo $userID_tmp;
									?>
								</td>
								<td style="text-align: center">
									<?php
										// Avatar
										if ($userAvatar_tmp) {
										   	// Récupère les dimensions
											list($width,$height)=GetThumbDim($userAvatar_tmp, $avatarMaxDim);
											$strtemp = '<img src="' . $userAvatar_tmp . '"';
											$strtemp = $strtemp . 'alt="" width="' . $width . '"';
											$strtemp = $strtemp . ' height="' . $height . '" style="border-width: 0px">';
											echo $strtemp;
										}
									?>
								</td>
								<td style="text-align: center">
									<?php
										// Pseudo
										echo '<a href="user.php?ID=' . $userID_tmp . '">' . $userName_tmp . '</a>';
									?>
								</td>
								<td style="text-align: center">
									<?php
										// Mail
										echo '<a href="mailto:' . $userMail_tmp . '">Ecrire</a>';
									?>
								</td>
								<td style="text-align: center">
									<?php
										// Website
										if ($userWebsite_tmp) {
											echo '<a href="' . $userWebsite_tmp . '" target="_blank">Visiter</a>';
										}
									?>
								</td>
							</tr>
							<?
						}
						dbClose();
					}
					else {
						echo "Impossible d'afficher la liste des admins";
					}
				?>
			</table>
			</div><br />
		<?
		include "windowbottom.php";
	?>


<?php include "bottom.php"; ?>
