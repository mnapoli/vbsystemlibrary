<?php
	$PageTitle = "Télécharger Vb System Library";
	$xitiPageName = "Telecharger";
	include "top.php";
	
	$versionID = GetLastVersionID();
?>

<!-- Contenu de la page -->


	<!-- Version -->
	<?php
		$WindowTitle = "Version de VbSysLib";
		include "windowtop.php";
			?>
			<p style="text-align: center; text-indent: 0; font-weight: bold; font-size: 17px">
			   	La version actuelle de Vb System Library est : <?php echo GetLastVersion(); ?>
			</p>
			<p style="font-size: 10px; text-indent: 0; text-align: center">
			   	Cette version a été téléchargée <?php echo GetVersionDL($versionID); ?> fois<br />
			   	Nombre de téléchargements total <?php echo GetNBTotalDL(); ?> fois<br />
			</p>
			<?php
		include "windowbottom.php";
	?>
	
	<!-- Télécharger la dll -->
	<?php
		$WindowTitle = "Télécharger la DLL";
		include "windowtop.php";
			?>
			<p>
			   	Cliquez sur télécharger la DLL pour commencer le téléchargement de la DLL (le code source n'est pas inclus) :
			</p>
			<p class="urlDL">
			    <a href="dlversion.php?id=<?php echo $versionID; ?>&amp;dll=1">Télécharger la DLL</a>
			</p>
			<?php
		include "windowbottom.php";
	?>

	<!-- Télécharger la source -->
	<?php
		$WindowTitle = "Télécharger le projet complet";
		include "windowtop.php";
			?>
			<p>
			   	Cliquez sur télécharger le code source et la DLL pour commencer le téléchargement de tout le projet complet :
			</p>
			<p class="urlDL">
			    	<a href="dlversion.php?id=<?php echo $versionID; ?>&amp;dll=2">Télécharger le code source et la DLL</a>
			</p>
			<p style="font-size: 10px; text-indent: 0; text-align: center">
			   	un exemple d'utilisation est compris
			</p>
			<?php
		include "windowbottom.php";
	?>


<?php include "bottom.php"; ?>

