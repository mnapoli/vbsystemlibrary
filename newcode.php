<?
	$PageTitle = "Ajouter un code";
	$xitiPageName = "AjouterCode";
	include "top.php";
?>

<!-- Contenu de la page -->
	<!-- Ajouter un code -->
	<?
		$WindowTitle = "Ajouter un code";
		include "windowtop.php";
		if ($Logged=="false") {
			?>
				<p>Vous devez vous connecter ou vous inscrire pour acceder à cette page.</p>
				<p><a href="newuser.php">S'incrire</a></p>
			<?
		}
		else {
					?>
					<br />
					<form action="oknewcode.php" method="post">
					
						<b>Titre du code :</b><br />
						<div style="margin-left: 10%;">
							<input class="Clair" type="text" name="title" maxlength="256" style="width: 100%"><br />
						</div><br />
							
						<b>Présentez votre code en quelques phrases :</b><br />
						<div style="margin-left: 10%;">
							<textarea class="Clair" name="presentation" rows="8" style="width: 100%"></textarea><br />
							<font size="1">Précisez ici quelle partie de la librairie est concernée, s'il s'agit de
							modifier ou d'ajouter une fonction etc...</font>
						</div><br />
							
						<b>Le code source :</b><br />
						<div style="margin-left: 10%;">
							<textarea class="Clair" name="code" rows="20" style="width: 100%"></textarea><br />
						</div><br />
						
						<div style="text-align: center">
							Cliquez ici pour soumettre votre code :<br /><br />
							<input type="submit" value="Envoyer">
						</div>
							
					</form>
					<br />
					<?php
		}
		include "windowbottom.php";
	?>

<?php include "bottom.php"; ?>
