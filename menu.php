<!-- Menu -->

	<div class="BoutonMenuTop">
		<a class="Menu" href="index.php">
			<img src="images/logo.png" border="0" align="middle" alt="Vb System Library">
		</a>
	</div>

	<div class="BoutonMenu">
		<a class="Menu" href="index.php">
			Accueil
		</a>
	</div>

	<div class="BoutonMenu">
		<a class="Menu" href="presentation.php">
			Présentation
		</a>
	</div>

	<div class="BoutonMenu">
		<img src="images/logo16.png" border="0" width="16" height="16" alt="icone vbsyslib" align="top" style="margin-top: -1px">
		<a class="Menu" href="download.php">
			Télécharger
		</a>
	</div>

	<div class="BoutonMenu">
		<a class="Menu" href="versions.php">
			Liste des versions
		</a>
	</div>
	
	<div class="BoutonMenu">
		<img src="images/logo16.png" border="0" width="16" height="16" alt="icone vbsyslib" align="top" style="margin-top: -1px">
		<a class="Menu" href="process-viewer.php" style="color: #8D0000">
			ProcessViewer
		</a>
	</div>

	<div class="BoutonMenu">
		<a class="Menu" href="documentation.php">
			Documentation
		</a>
	</div>

	<div class="BoutonMenu">
		<a class="Menu" href="news.php">
			News
		</a>
	</div>
	
	<div class="BoutonMenu">
		<a class="Menu" href="list.php">
			Les codes
		</a>
	</div>

	<div class="BoutonMenu">
		<a class="Menu" href="forum.php">
			Forum
		</a>
	</div>
	
	<div class="BoutonMenu">
		<a class="Menu" href="listmembres.php">
			Liste des membres
		</a>
	</div>
	
	<div class="BoutonMenu">
		<a class="Menu" href="newcode.php">
			Proposer un code
		</a>
	</div>
	
	<!-- Si on est pas enregistré : Inscription -->
	<?
		if ($Logged=="false") {
			?>
			<div class="BoutonMenu">
				<a class="Menu" href="newuser.php">
					Inscription
				</a>
			</div>
			<?
		}
		else {
			?>
			<div class="BoutonMenu">
				<a class="Menu" href="account.php">
					Votre compte
				</a>
			</div>
			<?
		}
	?>
	
	<?
		if (($Logged=='true') and ($userRights==1)) {
			?>
			<div class="BoutonMenu">
				<a class="Menu" href="admin.php">
					Administration
				</a>
			</div>
			<?
		}
	?>
	
	<?
		if (($Logged=='true') and ($userRights==1)) {
			?>
			<div class="BoutonMenu">
				<a class="Menu" href="upload.php">
					Uploader VbSysLib
				</a>
			</div>
			<?
		}
	?>

	<div class="BoutonMenu">
		<a class="Menu" href="livredor.php">
			Livre d'or
		</a>
	</div>
	
	<div class="BoutonMenu">
		<a class="Menu" href="partenaires.php">
			Partenaires
		</a>
	</div>
	
	<div class="BoutonMenu">
		<a class="Menu" href="contact.php">
			Nous contacter
		</a>
	</div>

	<!-- Login -->
	<div class="frmMenu">
		<!-- Affiche le formulaire de login -->
		<?
		if ($Logged=="false") {
			?>
			<h2 class="frmMenu">Se connecter</h2>
			<form class="frmLogin" name="log_user" action="index.php" method="post">
				<p class="frmMenu">Pseudo :</p>
				<input type="text" name="user" size="18" maxlength="256" class="frmLogin">
				<p class="frmMenu">Mot de passe :</p>
				<input type="password" name="pass" size="15" maxlength="256" class="frmLogin">
				&nbsp;<input type="submit" value="ok" alt="Valider">
			</form>
			<p class="frmMenu" style="text-align: center;"><a href="newuser.php">s'inscrire</a></p>
			<p class="frmMenu"><?php echo $ErrorLogged; ?></p>
			<?
		}
		else {
			?>
			<!-- Affiche les propriétés utilisateur -->
			<h2 class="frmMenu">Votre Compte</h2>
			<p class="frmMenu" style="font-weight: bold">Connecté en tant que :</p><br />
			<p class="frmMenu"><?php echo $userName; ?></p><br />
				<div style="text-align: center">
					<a href="user.php?ID=<?php echo $userID; ?>">Voir ma fiche</a><br />
					<a href="account.php">Modifier mon compte</a>
				</div>
			<div align="right">
				<a href="index.php?logout">Déconnexion</a>
			</div>
			<?
			}
		?>
	</div>
		
	<!-- Stats -->
	<div class="frmMenu">
		<h2 class="frmMenu">Statistiques</h2>
			<p class="frmMenu">
			Pages vues :
			<?
				// Nombre de pages vues
				if (@dbConnect() !=0) {
					$req = "SELECT `Value` FROM `stats` WHERE `Name`='TotalPageSeen'"; 
					$result = mysql_query($req);
					$rs = mysql_fetch_row($result);
					$nb = $rs[0] + 1;
					$req = "UPDATE `stats` SET `Value` = '$nb' WHERE `Name` = 'TotalPageSeen'"; 
					$result = mysql_query($req);
					dbClose();
					echo $nb;
				}
				else echo '#Erreur#';
			?><br />
			Téléchargements de la librairie :
			<?
				// Nombre de téléchargements
				echo GetNBTotalDL();
			?>
			</p>
			<p class="frmMenu">
			<?
				// Nombre de membres
				if (@dbConnect() !=0) {
					$req = "SELECT COUNT(*) FROM users WHERE Actif='1'";
					$result = mysql_query($req);
					$rs = mysql_fetch_row($result);
					$nb = $rs[0];
					dbClose();
					echo $nb;
				}
				else echo '#Erreur#';
			?>
			Membres
			</p>
			<p class="frmMenu">
			<?
				// Nombre de codes
				if (@dbConnect() !=0) {
					$req = "SELECT COUNT(*) FROM codes WHERE Actif='1'";
					$result = mysql_query($req);
					$rs = mysql_fetch_row($result);
					$nb = $rs[0];
					dbClose();
					echo $nb;
				}
				else echo '#Erreur#';
			?>
			Codes proposés
			</p>
			<p class="frmMenu" style="text-align: center; text-indent: 0px">
				<a href="http://www.xiti.com/xiti.asp?s=294511" title="WebAnalytics">
					<script type="text/javascript">
					<!--
					Xt_param = "s=294511&p=<?php echo $xitiPageName; ?>";
					try {Xt_r = top.document.referrer;}
					catch(e) {Xt_r = document.referrer; }
					Xt_h = new Date();
					Xt_i = '<img width="39" height="25" border="0" alt="" ';
					Xt_i += 'src="http://logv7.xiti.com/hit.xiti?'+Xt_param;
					Xt_i += '&hl='+Xt_h.getHours()+'x'+Xt_h.getMinutes()+'x'+Xt_h.getSeconds();
					if(parseFloat(navigator.appVersion)>=4)
					{Xt_s=screen;Xt_i+='&r='+Xt_s.width+'x'+Xt_s.height+'x'+Xt_s.pixelDepth+'x'+Xt_s.colorDepth;}
					document.write(Xt_i+'&ref='+Xt_r.replace(/[<>"]/g, '').replace(/&/g, '$')+'" title="Internet Audience">');
					//-->
					</script>
					<noscript>
					Mesure d'audience ROI statistique webanalytics par <img width="39" height="25" src="http://logv7.xiti.com/hit.xiti?s=294511&amp;p=<?php echo $xitiPageName; ?>" alt="WebAnalytics" />
					</noscript>
				</a>
			</p>
	</div>
