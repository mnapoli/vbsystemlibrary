<?
	$PageTitle = "ProcessViewer VbSysLib";
	$xitiPageName = "ProcessViewer";
	include "top.php";
?>

<!-- Contenu de la page -->
	<?
		$WindowTitle = "Télécharger ProcessViewer VbSysLib";
		include "windowtop.php";
	?>
		<p>
		   	Cliquez sur le lien pour télécharger le logiciel ProcessViewer VbSysLib
		</p>
		<p style="font-weight: bold; font-size: 14px; text-align: center; text-indent: 0px">
		    <a href="dlprocessviewer.php">Télécharger ProcessViewer VbSysLib</a><br />
			<span style="font-weight: normal; font-size: 11px">
			<?php
				// Nombre de téléchargements
				if (@dbConnect() !=0) {
					$req = "SELECT `Value` FROM `stats` WHERE `Name`='DLProcessViewer'"; 
					$result = mysql_query($req);
					$rs = mysql_fetch_row($result);
					dbClose();
					echo $rs[0] . ' téléchargements';
				}
			?>
			</span>
		</p>
		<p>
		   	Enregistrez le fichier sur votre ordinateur puis lancez l'installation.
		</p>
	<?
		include "windowbottom.php";
	?>
	<?
		$WindowTitle = "Télécharger la source de ProcessViewer VbSysLib";
		include "windowtop.php";
	?>
		<p>
		   	Cliquez sur le lien pour télécharger le code source du logiciel ProcessViewer VbSysLib
		</p>
		<p style="font-weight: bold; font-size: 14px; text-align: center; text-indent: 0px">
		    <a href="dlprocessviewersource.php">Télécharger le code source de ProcessViewer VbSysLib</a><br />
			<span style="font-weight: normal; font-size: 11px">
			<?php
				// Nombre de téléchargements
				if (@dbConnect() !=0) {
					$req = "SELECT `Value` FROM `stats` WHERE `Name`='DLProcessViewerSource'"; 
					$result = mysql_query($req);
					$rs = mysql_fetch_row($result);
					dbClose();
					echo $rs[0] . ' téléchargements';
				}
			?>
			</span>
		</p>
		<p>
			Attention, il s'agit du code source du programme, il ne s'agit pas du logiciel executable.
			Vous aurez donc besoin de Visual Basic 6 pour pouvoir l'executer.
			Ce téléchargement concerne surtout les développeurs.
		</p>
	<?
		include "windowbottom.php";
	?>

	<?
		$WindowTitle = "Présentation de ProcessViewer VbSysLib";
		include "windowtop.php";
	?>
		<p>
			ProcessViewer VbSysLib est un logiciel permettant de visualiser tous les processus
			tournant sur votre ordinateur. Ils sont présentés en arborescence afin de pouvoir
			voir "qui a lancé qui".<br /><br />
			Vous pouvez supprimer le processus, le mettre en pause (il sera completement inactif)
			et le relancer (après l'avoir mis en pause).
			Lorsqu'un processus est sélectionné, de nombreuses informations s'affichent dans la partie
			droite du programme.<br /><br />
			Vous pouvez y trouver des informations de type général :
			<ul type="disc">
				<li>Nom</li>
				<li>Programme auquel il appartient</li>
				<li>Nom de l'utilisateur à qui appartient le processus</li>
				<li>ID du processus</li>
				<li>Priorité actuelle</li>
				<li>Processus parent</li>
				<li>Chemin du fichier executable</li>
				<li>Description</li>
			</ul>
			Dans l'onglet "Performances" se trouve le graphe de l'utilisation CPU du processus (un pour
			chaque processus). Vous pourrez y trouver également diverses données sur son utilisation mémoire.
			<br /><br />
			Enfin l'onglet "Modules" donne la liste des modules chargés par le processus. Vous pouvez
			également décharger n'importe quel module de sa mémoire (à manipuler avec précaution).
		</p>
		<p>
			<span style="font-weight: bold">
				ProcessViewer VbSysLib est un logiciel développé en utilisant la VB System Library.
			</span>
			Cela permet de se rendre compte concretement de quoi est capable la librairie que
			nous développons. Cela est également un moyen de vous montrer les énormes avantages à l'utiliser
			(rapidité d'execution, facilité d'utilisation, invisible dans le programme final,
			structures intuitives, codes beaucoup plus court, développement plus rapide ...).
		</p><br />
		<p>
			Voici quelques captures du programme en fonctionnement :
		</p>
		<div align="center" style="margin-top: 25px; margin-bottom: 5px">
			<img src="images/processviewer/c1.jpg" border="0" alt="ProcessViewer VbSysLib">
		</div>
		<div align="center" style="margin-top: 10px; margin-bottom: 5px">
			<img src="images/processviewer/c2.jpg" border="0" alt="ProcessViewer VbSysLib">
		</div>
		<div align="center" style="margin-top: 10px; margin-bottom: 5px">
			<img src="images/processviewer/c3.jpg" border="0" alt="ProcessViewer VbSysLib">
		</div>
	
	<?
		include "windowbottom.php";
	?>

<?php include "bottom.php"; ?>

