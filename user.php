<?
	include "functions.php";
	$NotIncludeFonctions = 1;
	// Récupère le numéro du tutoriel
	@$userID_tmp = $_GET['ID'] or Error('Aucun membre précisé');
	// Récupère les infos associées
	list($userName_tmp, $userPass_tmp, $userRights_tmp, $userMail_tmp, $userAvatar_tmp, $userDate_tmp, $userNom_tmp, $userPrenom_tmp, $userWebsite_tmp, $userSexe_tmp, $userBirthDate_tmp, $userPublicMail_tmp) = GetUserInfos($userID_tmp);
	$PageTitle = $userName_tmp;
	$xitiPageName = "Utilisateur";
	include "top.php";
?>

<!-- Contenu de la page -->
	<!-- Catégories -->
	<?
		$WindowTitle = $userName_tmp;
		include "windowtop.php";
	?>
		<br />
		<div align="center"><div style="background: #2B295B; color: white; width: 90%; text-align: center">
			Informations Principales
		</div></div><br />
		<?php
		// Récupère les dimensions
		list($width,$height)=GetThumbDim($userAvatar_tmp, $avatarMaxDim);
		$strtemp = '<img src="' . $userAvatar_tmp . '"';
		$strtemp = $strtemp . 'alt="" width="' . $width . '"';
		$strtemp = $strtemp . ' height="' . $height . '" style="border-width: 0px">';
		echo $strtemp;
		?>
		Pseudo : <b><?php echo $userName_tmp; ?></b><br /><br />
		Membre enregistré depuis le <b><?php echo FormatDate($userDate_tmp); ?></b><br /><br />
		<?php
			if ($userPublicMail_tmp) {
			?>
				Mail : <b><a href="mailto:<?php echo $userPublicMail_tmp; ?>"><?php echo $userPublicMail_tmp; ?></a></b><br /><br />
			<?php
			}
		?>
		Cet utilisateur est <b><?php echo GetRightsName($userRights_tmp); ?></b><br /><br /><br />
		
		<div align="center"><div style="background: #2B295B; color: white; width: 90%; text-align: center">
			Informations Personnelles
		</div></div><br />
		<?php
			if ($userNom_tmp) { echo 'Nom : <b>' . $userNom_tmp . '</b><br /><br />'; }
			if ($userPrenom_tmp) { echo 'Prénom : <b>' . $userPrenom_tmp . '</b><br /><br />'; }
			if ($userWebsite_tmp) { echo 'Site Perso : <b><a href="' . $userWebsite_tmp . '" target="_blank">' . $userWebsite_tmp . '</a></b><br /><br />'; }
			if ($userBirthDate_tmp != '0000-00-00') { echo 'Date de naissance : <b>' . FormatDate($userBirthDate_tmp) . '</b><br /><br />'; }
			if ($userSexe_tmp) { echo 'Sexe : <b>' . $userSexe_tmp . '</b><br />'; }
		?>
		<br />
		
		<div align="center"><div style="background: #2B295B; color: white; width: 90%; text-align: center">
			Codes
		</div></div>
		<p style="text-align: center; text-indent: 0">
			<a href="list.php?userid=<?php echo $userID_tmp; ?>">
			   	Voir les code de ce membre
			</a>
		</p><br />
			
	<?
		include "windowbottom.php";
	?>

<?php include "bottom.php"; ?>
