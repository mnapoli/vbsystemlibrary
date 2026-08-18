<!-- Affiche un message et ses réponses -->
<?php
	// Récupération des propriétés du post
	list($postIDUser, $postIDParent, $postTitle, $postText, $postIDCateg, $postDate, $postTime, $postIP) = GetPostInfos($postID);
	if (!$postIDUser) {
		Redirect("forum.php");
	}
	list($userName_tmp, $userPass_tmp, $userRights_tmp, $userMail_tmp, $userAvatar_tmp, $userDate_tmp, $userNom_tmp, $userPrenom_tmp, $userWebsite_tmp, $userSexe_tmp, $userBirthDate_tmp, $userPublicMail_tmp) = GetUserInfos($postIDUser);
	$userNBPost_tmp = GetUserNBPost($postIDUser);
	$WindowTitle = $postTitle;
	include "windowtop.php";
	?>
		<br />
		<a href="forum.php?categ=<?php echo $postIDCateg; ?>">&lt; Retour au sommaire de la catégorie</a><br />
		<a href="forum.php">&lt;&lt; Retour au sommaire du forum</a><br /><br />
		<!-- Affiche le post -->
		<div class="PostTitle">
			<strong><?php echo $postTitle; ?></strong> par <?php echo $userName_tmp; ?>
			<span style="font-size: 10px">le <?php echo FormatDate($postDate); ?> à <?php echo $postTime; ?></span>
			&nbsp; &nbsp; "<?php echo GetCatName($postIDCateg); ?>"
		</div>
		<?php
			// Panneau de gestion
			if ($Logged=='true' and(($userRights == '1') or ($postIDUser == $userID))) {
				?>
				<div class="PostGestion">
					Panneau de gestion :
					<a href="forum.php?ID=<?php echo $postID . '&amp;deletepost=' . $postID; ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce message ?');">
						<img src="images/croix.gif" width="16" height="16" alt="Supprimer le post" style="vertical-align: middle">
						Supprimer le post (supprimera toutes les réponses)
					</a>
					&nbsp; &nbsp;
					<span style="color: #000066">
						Adresse IP :
						<?php echo $postIP; ?>
					</span>
					&nbsp; &nbsp;
					<span style="color: #000066">
						Mail :
						<?php echo $userMail_tmp; ?>
					</span>
				</div>
				<?php
			}
		?>
		<div class="PostContent">
			<table>
				<tr>
					<td class="PostAuteur">
					<!-- <div class="PostAuteur"> -->
						<a href="user.php?ID=<?php echo $postIDUser; ?>"><?php echo $userName_tmp; ?></a><br />
						<div style="text-align: center">
							<?php if ($userAvatar_tmp != '') echo "<img src='" . $userAvatar_tmp . "' border='0' align='middle' alt='" . $userName_tmp . "'> "; ?>
						</div><br />
						<b><?php echo GetRightsName($userRights_tmp); ?></b><br /><br />
						<span style="white-space: nowrap;">
						Inscrit le <?php echo FormatDate($userDate_tmp); ?><br />
						</span>
						<span style="white-space: nowrap;">
						<?php echo $userNBPost_tmp; ?> Message(s)
						</span>
					<!-- </div> -->
					</td>
					<td class="PostText" style="border-width: 2px; border-color: black">
						<p>
							<?php echo DeFormatText($postText); ?>
						</p>
					</td>
				</tr>
			</table>
		</div>
		
		<!-- Affiche les réponses -->
		<?php
		// Récupération des réponses
		$Replies = GetReplies($postID);
		if ($Replies) {
		foreach($Replies as $key => $postID_tmp) {
			list($postIDUser_tmp, $postIDParent_tmp, $postTitle_tmp, $postText_tmp, $postIDCateg_tmp, $postDate_tmp, $postTime_tmp, $postIP_tmp) = GetPostInfos($postID_tmp);
			list($userName_tmp, $userPass_tmp, $userRights_tmp, $userMail_tmp, $userAvatar_tmp, $userDate_tmp, $userNom_tmp, $userPrenom_tmp, $userWebsite_tmp, $userSexe_tmp, $userBirthDate_tmp, $userPublicMail_tmp) = GetUserInfos($postIDUser_tmp);
			$userNBPost_tmp = GetUserNBPost($postIDUser_tmp);
			?>
				<!-- Affiche le post -->
				<div class="PostTitle">
					<strong><?php echo $postTitle_tmp; ?></strong> par <?php echo $userName_tmp; ?> <span style="font-size: 10px">le <?php echo FormatDate($postDate_tmp); ?> à <?php echo $postTime_tmp; ?></span>
				</div>
				<?php
					// Panneau de gestion
					if ($Logged=='true' and(($userRights == '1') or ($postIDUser_tmp == $userID))) {
						?>
						<div class="PostGestion">
							Panneau de gestion :
							<a href="forum.php?ID=<?php echo $postID . '&amp;deletepost=' . $postID_tmp; ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce message ?');">
								<img src="images/croix.gif" width="16" height="16" alt="Supprimer le post" style="vertical-align: middle">
								Supprimer la réponse
							</a>
							&nbsp; &nbsp;
							<span style="color: #000066">
								Adresse IP :
								<?php echo $postIP_tmp; ?>
							</span>
							&nbsp; &nbsp;
							<span style="color: #000066">
								Mail :
								<?php echo $userMail_tmp; ?>
							</span>
						</div>
						<?php
					}
				?>
				<div class="PostContent">
					<table>
						<tr>
							<td class="PostAuteur">
								<a href="user.php?ID=<?php echo $postIDUser_tmp; ?>"><?php echo $userName_tmp; ?></a>
								<div style="text-align: center">
									<?php if ($userAvatar_tmp != '') echo "<img src='" . $userAvatar_tmp . "' border='0' align='middle' alt='" . $userName_tmp . "'> "; ?>
								</div><br />
								<b><?php echo GetRightsName($userRights_tmp); ?></b><br /><br />
								<span style="white-space: nowrap;">
									Inscrit le <?php echo FormatDate($userDate_tmp); ?><br />
								</span>
								<span style="white-space: nowrap;">
									<?php echo $userNBPost_tmp; ?> Message(s)
								</span>
							</td>
							<td class="PostText"<?php if ($postIDUser_tmp == $postIDUser) echo ' style="border-width: 2px; border-color: black"';?>>
								<p>
									<?php echo DeFormatText($postText_tmp); ?>
								</p>
							</td>
						</tr>
					</table>
				</div>
			<?php
		}
		}
		?>
		
		<br />
		<!-- Répondre -->
		<div class="PostTitle">
			Ajouter une réponse
		</div>
		<div class="PostContent">
			<div class="ReplyText">
			<?php
			// On propose une réponse si on est connecté
			if ($Logged=="false") {
				?>
				<p>Vous devez vous connecter ou vous inscrire pour pouvoir répondre.</p>
				<p><a href="newuser.php">S'incrire</a></p>
				<?
			}
			else {
				?>
				<form name="postReply" action="forum.php" method="post">
					<input type="hidden" name="postParent" value="<?php echo $postID; ?>">
					<div class="conteneurRTE"><span class="rteColumn">
						<script language='JavaScript' type='text/javascript'>
							initRTE('conceptrte/images/', '', 'conceptrte/style.css', 'FR');
							// x,x,width,height
							writeRichText('rte', '', '100%', '250px', true, false);
						</script>
					</span></div>
					<input type="hidden" name='replyText'>
					<input type="hidden" name="isRTE">
					<!-- <textarea name="postReply" rows="10" style="width: 100%" class="Clair"></textarea> -->
					<p>Nous vous prions de rester courtois dans vos commentaires ainsi que de ne pas utiliser
						de langage de type "sms", merci.	Les modérateurs se réservent le droit d'effacer les messages ne
						respectants pas cette règle.</p>
					<div style="text-align: right">
						<!-- <input type="submit" value="Ajouter"> -->
						<input class=button type=button value='Ajouter' onClick="updateRTE('rte'); postReply.isRTE.value = isRichText; postReply.replyText.value = postReply.rte.value; this.form.submit();">
					</div>
				</form>
				<?php
			}
			?>
			</div>
		</div>
		<br />
	<?php
	include "windowbottom.php";
?>
