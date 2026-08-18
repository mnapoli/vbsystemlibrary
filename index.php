<?php
	$PageTitle = "Vb System Library";
	$xitiPageName = "Index";
	$IncludeCSS_Forum = 1;
	include "top.php";
?>

<!-- Contenu de la page -->


	<!-- Présentation -->
	<?php
		$WindowTitle = "Présentation du projet VbSystemLibrary";
		include "windowtop.php";
			?>
			<p>
				Bienvenue sur le site web consacré au développement de Vb System Library.<br />
				Si vous vous demandez ce qu'est Vb System Library, voici une page d'explication :
			</p>
			<p>
				<a href="presentation.php" style="font-weight: bold">Presentation de Vb System Library</a>
			</p>
			<p>
				Pour télécharger la librairie :
			</p>
			<p>
				<a href="download.php" style="font-weight: bold">Télécharger Vb System Library</a>
			</p>
			<p style="font-weight: bold; font-size: 13px">
				Version actuelle : <?php echo GetLastVersion(); ?>
			<p>
				Bonne prog ;)<br />
				L'équipe Vb System Library
			</p>
			<?php
		include "windowbottom.php";
	?>
	
	<!-- Les News -->
	<?php
		$WindowTitle = "News";
		include "windowtop.php";
			?>
		  	<br />
			<div>
				<?php
					include "previewnews.php";
				?>
				<div style="text-align: right; font-size: 10px"><a href="news.php">Les news suivantes</a></div>
			</div><br />
			<?php
		include "windowbottom.php";
	?>

	<!-- Derniers codes -->
	<?
		$WindowTitle = "Derniers codes";
		include "windowtop.php";
		?>
		  	<br />
			<div align="center">
				<?php
					$listMin = 0;
					$listMax = 10;
					$listTri = 'b';
					include 'listcodes.php';
				?>
			</div>
			<br />
			<div style="text-align: right"><a href="newcode.php">Ajouter un code</a></div>
			<div style="text-align: right"><a href="list.php">Voir la suite</a></div>
			<br />
		<?php
		include "windowbottom.php";
	?>
	
	
	<!-- Derniers messages -->
	<?php include "forum/listpost.php"; ?>
	

<?php include "bottom.php"; ?>

