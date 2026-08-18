<!-- Nouveau message -->
<?
	$WindowTitle = "Ajouter un message";
	include "windowtop.php";
	?>
		<br />
		<?php
			if (isset($postIDCateg)) echo '<a href="forum.php?categ=' . $postIDCateg . '">&lt;&lt; Retour au sommaire de la catégorie</a><br />';
		?>
		<a href="forum.php">&lt;&lt;&lt; Retour au sommaire du forum</a><br />
		<?php
		if ($Logged=="false") {
			?>
			<p>Vous devez vous connecter ou vous inscrire pour accéder à cette page.</p>
			<p><a href="newuser.php">S'inscrire</a></p>
			<?
		}
		else {
			?>
			<br />
			<!-- Ajouter un message -->
			<div class="PostTitle">
				Ajouter un message
			</div>
			<div class="PostContent">
				<div class="ReplyText">
					<?php
					if (isset($postTitle)) {
						?>
						<div style="text-align: center; font-size: 12px; color: red; font-weight: bold">
							Veuillez remplir tous les champs
						</div>
						<?php
					}
					?>
					<form name="postNew" action="forum.php" method="post">
						Titre de votre message :<br />
						<input type="text" name="postTitle" maxlength="100" style="width: 100%" class="Clair" value="<?php if (isset($postTitle)) echo $postTitle; ?>"><br /><br />
						Contenu de votre message :<br />
						<div class="conteneurRTE"><span class="rteColumn">
							<script language='JavaScript' type='text/javascript'>
								initRTE('conceptrte/images/', '', 'conceptrte/style.css', 'FR');
								// x,x,width,height
								//< ?php if (isset($postText)) echo "'$postText'"; else echo "''"; ?>
								writeRichText('rte', '', '100%', '300px', true, false);
							</script>
						</span></div>
						<input type="hidden" name='conceptRTEvalue'>
						<input type="hidden" name="isRTE">
						<p>Nous vous prions de rester courtois dans vos commentaires ainsi que de ne pas utiliser
							de langage de type "sms", merci.	Les modérateurs se réservent le droit d'effacer les messages ne
							respectant pas cette règle.</p><br /><br />
						<hr width="90%" size="1" noshade style="color: #7375d3; background-color: #7375d3; border-color: #7375d3; margin-bottom: 15px">
						<span style="font-weight: bold">Catégorie :</span>
						<select name="postCateg">
		               		   <option value="">Veuillez sélectionner une catégorie</option>
								<?php
									$Categ = GetCateg();
									$t = 0;
									while (isset($Categ[$t])) {
								?>
						 			<option value="<?php echo $Categ[$t][0]; ?>"<?php
											if (isset($postIDCateg)) {
												if ($postIDCateg == $Categ[$t][0]) echo ' selected';
											}
										?>><?php echo $Categ[$t][1]; ?></option>
								<?php
										$t = $t + 1;
									}
								?>
		               	</select><br /><br />
						<hr width="90%" size="1" noshade style="color: #7375d3; background-color: #7375d3; border-color: #7375d3; margin-bottom: 15px">
						<div style="text-align: center">
							<input class=button type=button value='Ajouter' onClick="updateRTE('rte'); postNew.isRTE.value = isRichText; postNew.conceptRTEvalue.value = postNew.rte.value; this.form.submit();">
						</div>
					</form>
				</div>
			</div>
			<br />
			<?php
		}
		?>
	<?php
	include "windowbottom.php";
?>
