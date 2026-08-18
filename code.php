<?php
	include 'functions.php';
	$xitiPageName = "Code";
	$NotIncludeFonctions = 1;
	// Récupère le numéro du code
	@$codeID = $_GET['ID'] or die ("aucun code défini");
	// Récupère les infos associées
	if (@dbConnect() !=0) {
		$req = "SELECT `ID`, `IDuser`, `title`, `presentation`, `code`, `date`, `time`, `Historique` FROM `codes` WHERE `ID` = '$codeID' AND `Actif` = '1'"; 
		$result = mysql_query($req);
		$nbresult = mysql_num_rows($result);
		if ($nbresult!=0) {
			$rs = mysql_fetch_row($result);
				$codeIDuser = $rs[1];
				$codeTitle = $rs[2];
				$codePresentation = $rs[3];
				$codeCode = $rs[4];
				$codeDate = $rs[5];
				$codeTime = $rs[6];
				$codeHistorique = $rs[7];
			dbClose();
			// On recherche l'auteur
			list($codeUserName, $userPass_tmp, $userRights_tmp, $codeUserMail, $codeUserAvatar, $userDate_tmp, $userNom_tmp, $userPrenom_tmp, $userWebsite_tmp, $userSexe_tmp, $userBirthDate_tmp) = GetUserInfos($codeIDuser);
			// On recherche les commentaires
			$codeTableauCommentaires = GetCommFromCode($codeID);
			$codeNBCommentaires = count($codeTableauCommentaires);
			if ($codeTableauCommentaires == 0) {
			   	$codeNBCommentaires = 0;
			}
			else {
				foreach($codeTableauCommentaires as $key => $commID_tmp) {
					list($commUser_tmp, $commDate_tmp, $commTime_tmp, $commText_tmp, $commIP_tmp) = GetCommInfos($commID_tmp);
					list($userName_tmp, $userPass_tmp, $userRights_tmp, $userMail_tmp, $userAvatar_tmp) = GetUserInfos($commUser_tmp);
					// Cherche les infos de l'auteur du commentaire
					if (!isset($tabUser[$commUser_tmp])) {
						$tabUser[$commUser_tmp]['ID'] = $commUser_tmp;
						$tabUser[$commUser_tmp]['Name'] = $userName_tmp;
						$tabUser[$commUser_tmp]['Avatar'] = $userAvatar_tmp;
						$tabUser[$commUser_tmp]['Rights'] = $userRights_tmp;
						$tabUser[$commUser_tmp]['Mail'] = $userMail_tmp;
					}
					$codeTableauUserComm[$key] = $commUser_tmp;
					$codeTableauDateComm[$key] = $commDate_tmp;
					$codeTableauTimeComm[$key] = $commTime_tmp;
					$codeTableauTextComm[$key] = $commText_tmp;
					$codeTableauIPComm[$key] = $commIP_tmp;
					$codeTableauMailComm[$key] = $userMail_tmp;
				}
			}
		}
		else {
			echo "Ce code n'existe plus.";
			exit();
		}
	}
	else {
		Error('Impossible de se connecter à la base.');
	}
	
	
	$PageTitle = $codeTitle;
	include "top.php";
	
	// Nouveau commentaire
	if (isset($_POST['commText'])) {
		$commText = FormatText($_POST['commText']);
		if ($commText) {
			AddComm($codeID, $userID, $commText);
			// Envoie un mail à l'auteur
			// Si ça n'est pas l'auteur qui a posté le commentaire
			if ($userID != $codeIDuser) {
			   	AlertOwnSource($codeTitle, $codeID, $codeUserMail, $codeUserName);
			}
			// Envoie un mail à tous les auteurs des commentaires
			foreach($tabUser as $key => $userID_tmp) {
				// Si ça n'est pas l'auteur ni celui qui a posté le commentaire
				if (($tabUser[$key]['ID'] != $codeIDuser) and ($tabUser[$key]['ID'] != $userID)) {
				   	AlertSource($codeTitle, $codeID, $tabUser[$key]['Mail'], $tabUser[$key]['Name']);
				}
			}
			Redirect('code.php?ID=' . $codeID);
		}
	}
	// Supprime commentaire
	if (isset($_GET['deletecomm'])) {
		$commID = FormatText($_GET['deletecomm']);
		if ($commID) {
			DelComm($commID);
			Redirect('code.php?ID=' . $codeID);
		}
	}
?>

<!-- Contenu de la page -->
	<!-- code -->
	<?php
		$WindowTitle = $codeTitle;
		include "windowtop.php";
	?>
		<br />
		<!-- Propriétés -->
		<?php
			$WindowTitle = "Propriétés du code";
			include "windowtop.php";
		?>
			<br /><table width="100%" align="center">
				<tr>
					<td style="font-family: verdana; font: 11" width="50%">
						<!-- Stats -->
						Date de création : <?php echo FormatDate($codeDate); ?> à <?php echo $codeTime; ?><br>
						<a href="#idCommentaires">
						   	<?php echo $codeNBCommentaires; ?> Commentaire(s)</a>
						<br>
					</td>
					<td style="font-family: verdana; font: 11" width="50%">
						<!-- L'auteur -->
						<?php
							// Récupère les dimensions
							list($width,$height)=GetThumbDim($codeUserAvatar, $avatarMaxDim);
							$strtemp = '<img src="' . $codeUserAvatar . '"';
							$strtemp = $strtemp . 'alt="" ';
							$strtemp = $strtemp . ' align="middle" style="border-width: 0px">';
							echo $strtemp;
							echo "<b>&nbsp; <a href='user.php?ID=" . $codeIDuser . "'>";
							echo $codeUserName . "</a></b><br />";
						?>
					</td>
				</tr>
			</table>
			<br />
		<?php
			include "windowbottom.php";
		?>

		<!-- Présentation par l'auteur -->
		<?php
			if ($codePresentation != '') {
				$WindowTitle = "Présentation";
				include "windowtop.php";
					echo '<br />' . $codePresentation . '<br /><br />';
				include "windowbottom.php";
			}
		?>

		<!-- Code -->
		<?php
			$WindowTitle = "Code";
			include "windowtop.php";
				echo '<br /><p class="Code">' . $codeCode . '</p><br />';
			include "windowbottom.php";
		?>
		
		<!-- Historique -->
		<?php
			if ($codeHistorique != '') {
				$WindowTitle = 'Historique';
				include 'windowtop.php';
					echo '<br />Voici l\'historique des modifications de ce code :
							<blockquote class=\'Clair\'>' . $codeHistorique . '</blockquote><br />';
				include 'windowbottom.php';
			}
		?>
		
	<?php
		include "windowbottom.php";
	?>
			
	<!-- Modifier -->
	<?php
		$WindowTitle = 'Modifier le code';
		include "windowtop.php";
		if ($Logged == 'false' or ($userRights != '1' and $userID != $codeIDuser)) {
			?>
			<p>Seul les admins et l'auteur du code lui même peuvent modifier ce code.</p>
			<?php
		}
		else {
			?>
			<p>
				L'auteur du code et les administrateurs peuvent modifier ce code :
			</p>
			<p style="text-align: center; font-weight: bold">
			   	<a href="modifcode.php?ID=<?php echo $codeID; ?>">Modifier le code</a>
			</p>
			<?php
		}
		include "windowbottom.php";
	?>
			
	<!-- Modérer -->
	<?php
		if (($Logged == 'true') and ($userRights == '1')) {
			$WindowTitle = 'Panneau administrateur';
			include "windowtop.php";
			?>
			<p>
				Vous pouvez modifier le code :
			</p>
			<p style="text-align: center; font-weight: bold">
			   	<a href="modifcode.php?ID=<?php echo $codeID; ?>">Modifier le code</a>
			</p>
			<p>
				Vous pouvez désactiver le code :
			</p>
			<p style="text-align: center; font-weight: bold">
			   	<a href="deletecode.php?ID=<?php echo $codeID; ?>" onclick="return confirm('Voulez-vous vraiment désactiver ce code ?');">Désactiver le code</a>
			</p>
			<?php
			include "windowbottom.php";
		}
	?>
	
	<!-- Commentaires -->
	<?php
		$WindowTitle = 'Commentaires';
		include "windowtop.php";
	?>
		<div id="idCommentaires"><br />
		</div>
		<!-- Chaque commentaire -->
		<?php
			if ($codeNBCommentaires != 0) {
				foreach($codeTableauCommentaires as $key => $commID_tmp) {
					$WindowTitle = 'De <a href="user.php?ID=' . $tabUser[$codeTableauUserComm[$key]]['ID'] . '">' . $tabUser[$codeTableauUserComm[$key]]['Name'] . '</a> le ' . FormatDate($codeTableauDateComm[$key]) . ' à ' . substr($codeTableauTimeComm[$key], 0, 5);
					include "windowtop.php";
						?>
							<br />
							<div style="width: 100%; overflow: hidden">
								<?php
									if ($tabUser[$codeTableauUserComm[$key]]['Avatar'] != '') {
										$strtemp = '<img src="' . $tabUser[$codeTableauUserComm[$key]]['Avatar'] . '"';
										$strtemp = $strtemp . 'alt=""';
										$strtemp = $strtemp . ' align="middle" style="border-width: 0px">';
										echo $strtemp;
									}
									echo $codeTableauTextComm[$key];
								?>
							</div>
							<br />
							<?php
								// Panneau de gestion
								if ($Logged=='true' and (($userRights == '1') or ($codeTableauUserComm[$key] == $userID))) {
									?>
									<div class="CommentGestion">
										Panneau de gestion :
										<a href="code.php?ID=<?php echo $codeID . '&amp;deletecomm=' . $commID_tmp; ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce message ?');">
											<img src="images/croix.gif" width="16" height="16" alt="Supprimer le commentaire" style="vertical-align: middle">
											Supprimer le commentaire</a>
										&nbsp; &nbsp;
										<span style="color: #000066">
											Adresse IP :
											<?php echo $codeTableauIPComm[$key]; ?>
										</span>
										&nbsp; &nbsp;
										<span style="color: #000066">
											Mail :
											<?php echo $codeTableauMailComm[$key]; ?>
										</span>
									</div>
									<br />
									<?php
								}
							?>
						<?php
					include "windowbottom.php";
				}
			}
		?>
			
		<!-- Nouveau commentaire -->
		<?php
			$WindowTitle = 'Ajouter un commentaire';
			include "windowtop.php";
			if ($Logged == 'false') {
				?>
				<br />Vous devez être connecté pour pouvoir poster un commentaire.<br /><br />
				<?php
			}
			else {
				?>
				<form name="comm" action="code.php<?php echo '?ID=' . $codeID; ?>" method="post">
					<textarea name="commText" cols="1" rows="7" style="width: 95%; word-wrap: break-word" class="Clair"></textarea><br />
					<p><font size="-2">Nous vous prions de rester courtois dans vos commentaires ainsi que de ne pas utiliser
					de langage de type "sms", merci.</font></p>
					<div style="text-align: right"><input type="submit" value="Ajouter"></div>
				</form>
				<?php
			}
			include "windowbottom.php";
		?>
	<?php
		include "windowbottom.php";
	?>

<?php include "bottom.php"; ?>

