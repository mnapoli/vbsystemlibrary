<?
	$PageTitle = "Présentation du projet VbSystemLibrary";
	$xitiPageName = "Presentation";
	include "top.php";
?>

<!-- Contenu de la page -->


	<!-- Les News -->
	<?
		$WindowTitle = "Présentation";
		include "windowtop.php";
			?>
			<p>
			   Voici une petite présentation du projet Vb System Library.<br />
			   Mais avant tout, étant donné
			   qu'un beau dessin parle beaucoup plus qu'un long discours, voici une très simple représentation
			   de l'architecture objet qui sera contenue dans la VbSysLib à terme (j'ai utilisé la modélisation
			   UML par simplicité, je n'ai pas forcément respecté les standards).
			</p>
			<div align="center">
			<img src="images/UML.gif" border="1" width="660" height="513" alt="Modélisation UML de Vb System Library" align="middle">
			<br />
			si vous voulez proposer des améliorations, n'hésitez pas à nous contacter
			</div> <br />
			<p>
			   Nous sommes une équipe de programmeur en Visual Basic et nous souhaitons développer une librairie
			   permettant de récupérer des informations et d'agir sur le système Windows. En effet, aujourd'hui avec
			   l'arrivée des langages .net et de leur FrameWork, il devient très aisé de manipuler le système dans ces
			   langages, tandis qu'il est toujours difficile d'en faire autant avec les "anciens langages", dont celui dans
			   lequel nous developpons : Visual Basic.
			</p>
			<p>
			   C'est donc pour permettre à tout le monde, même au débutants, et de faciliter la tâche aux initiés que nous
			   avons décidé de développer une sorte d'équivalent au FrameWork .net pour Visual Basic 6 et antérieur.<br />
			   Ce projet se présenterait sous la forme d'une DLL ActiveX contenant à la fois une énorme liste de fonctions
			   optimisées pour agir
			   simplement sur le système, mais également tout une modélisation du système Windows grâce à des objets
			   afin de pouvoir récupérer des informations et agir simplement dessus.
			</p>
			<p>
			   Ce projet est Open Source, c'est à dire que n'importe qui peut consulter les codes sources de Vb System Library,
			   et peut également participer au développement grâce à ce site web. Pour cela il suffit juste de vous inscrire
			   puis de proposer des bouts de code qui pourraient être ajoutés dans la librairie. Ces bouts de codes sont alors
			   exposés publiquement afin que tout le monde puisse les commenter et participer à leur amélioration. Lorsqu'un
			   administrateur a décidé que ce bout de code pouvait être ajouté à la librairie, les sources sont alors modifiés
			   et la DLL est mise à jour.<br />
			   Ainsi, ce projet est celui de tout le monde, pour tout le monde.
			</p>
			<p>
			   Bonne prog ;)<br />
			   L'équipe Vb System Library
			</p>
			<?
		include "windowbottom.php";
	?>

	<!-- Infos mises à jour -->
	<?
		$WindowTitle = "Infos mises à jour";
		include "windowtop.php";
			?>
			<p>
			   Concernant les mises à jour de la librairie, nous faisons tout notre possible pour rendre les différentes
			   versions compatibles entre elles, c'est à dire que l'utilisateur ne doit normalement pas avoir à
			   changer le code qui appelle la librairie lorsqu'il met à jour la dll. Cependant nous ne pouvons pas garantir
			   cela, nous vous conseillons donc, avant de télécharger une nouvelle version, de vous renseigner sur les
			   changements qui ont été fait afin de pouvoir vous adapter (ceci peut être consulté dans les news).
			</p>
			<p>
			   De toute façon, si vous hésitez et que votre version de la librairie vous convient, il n'est pas alors
			   essentiel de faire une mise à jour ;).
			</p>
			<p style="text-align: center">
			   <a href="versions.php">Liste des mises à jour</a>
			</p>
			<?
		include "windowbottom.php";
	?>


<?php include "bottom.php"; ?>

