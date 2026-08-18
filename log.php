<?
	$PageTitle = "Journal";
	$xitiPageName = "Journal";
	include 'top.php';
	
	// Vérifie l'identité de l'utilisateur
	if (($Logged=='false') or ($userRights != 1)) Error('Vous n\'avez pas l\'autorisation de voir cette page.', 'log.php');
	
	// Vide le journal
	if (isset($_POST['clearLogErrors'])) {
		$handle = fopen("log/errors.log", "w+");
		fclose($handle);
	}
	
	// Page normale
		?>
		
			<?
				$WindowTitle = 'Page réservée';
				include 'windowtop.php';
			?>
				<p style="text-align: center; font-weight: bold; color: #FF0000">/!\ Page réservée à l'administrateur /!\</p>
			<?
				include 'windowbottom.php';
			?>
		
			<?
				$WindowTitle = 'Journal des erreurs';
				include 'windowtop.php';
			?>
				<p>Voici le contenu du journal des erreurs :</p>
				<?
					$tabLog = file("log/errors.log");
					while(list($cle,$val) = each($tabLog)) {
   						echo $val."<br />";
					}
				?>
				<br />
			<?
				include 'windowbottom.php';
			?>
		
			<?
				$WindowTitle = 'Vider le journal des erreurs';
				include 'windowtop.php';
			?>
				<p>Voulez vous vider le journal</p>
				<form name="errorsLog" action="log.php" method="post">
					<input type="hidden" name="clearLogErrors">
					<input type="submit" value="Oui">
				</form><br />
			<?
				include 'windowbottom.php';
			?>
		
		<?php

include 'bottom.php';
?>
