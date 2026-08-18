<?php
	$PageTitle = "Forum - Vb System Library";
	$xitiPageName = "Forum";
	$IncludeCSS_Forum = 1;
	$IncludeCSS_RTE = 1;
	$LoadConceptRTE = 1;
	include "top.php";
?>

<!-- Contenu de la page -->
<?php
	// On cherche quelle page on veut atteindre

	// Récupère le numéro du post
	if (isset($_GET['ID'])) $postID = $_GET['ID'];
	// Récupère la suppression d'un post
	if (isset($_GET['deletepost'])) $DeletePostID = $_GET['deletepost'];
	// Récupère la catégorie
	if (isset($_GET['categ'])) $postIDCateg = $_GET['categ'];
	// Si on demande d'afficher seulement les listes de tutoriels
	if (isset($_GET['list'])) $postList = 1;
	// Page d'ajout
	if (isset($_GET['ajout'])) $postAjout = 1;
	// Min et Max
	if (isset($_GET['min'])) $postMin = $_GET['min'];
	if (isset($_GET['max'])) $postMax = $_GET['max'];
	// Ajout d'un message
	if (isset($_POST['isRTE'])){ $isRichText = $_POST['isRTE']; } else { $isRichText = 'false'; }
	if (isset($_POST['conceptRTEvalue'])) $postText = $_POST['conceptRTEvalue'];
	
	
	// Ajout d'un message
	if (isset($postAjout)) {
		include 'forum/addpost.php';
	}
	elseif (isset($postText)) {
		// Vérifie
		if (isset($_POST['postTitle']) and isset($_POST['postCateg'])) {
			// Récupère les infos
			$postTitle = $_POST['postTitle'];
			$postIDCateg = $_POST['postCateg'];
			$postIDUSer = $userID;
			$postIDParent = 0;
			if ($postText != '' and $postTitle != '' and $postIDCateg != '') {
				$postText = FormatText($postText);
				// Si c'est une textarea simple : double formatage
				if ($isRichText=='false') $postText = FormatText($postText);
				$postTitle = FormatText($postTitle);
				AddPost($postIDUSer, $postIDParent, $postTitle, $postText, $postIDCateg);
				Redirect('forum.php');
			}
			else {
				include 'forum/addpost.php';
			}
		}
	}
	// Ajout d'une réponse
	elseif (isset($_POST['replyText'])) {
		// Récupère les infos
		$postIDParent = $_POST['postParent'];
		$postText = FormatText($_POST['replyText']);
		// Si c'est une textarea simple : double formatage
		if ($isRichText=='false') $postText = FormatText($postText);
		list($postIDUser_tmp, $postIDParent_tmp, $postTitle, $postText_tmp, $postIDCateg, $postDate_tmp, $postTime_tmp) = GetPostInfos($postIDParent);
		$postTitle = 'Re : ' . $postTitle;
		$postIDUSer = $userID;
		if ($postText != '') {
			AddPost($postIDUSer, $postIDParent, $postTitle, $postText, $postIDCateg);
			// Envoie les mails
			AlertPost($postIDParent);
			Redirect('forum.php?ID=' . $postIDParent);
		}
		else {
			include 'forum/addpost.php';
		}
	}
	// Supprimer un post
	elseif (isset($postID) and isset($DeletePostID)) {
		include 'forum/deletepost.php';
		DelPost($DeletePostID);
		include 'forum/post.php';
	}
	// Affiche la page correspondante
	// Visualisation d'un message
	elseif (isset($postID)) {
		include 'forum/post.php';
	}
	// Liste des messages
	elseif (isset($postIDCateg)) {
		include 'forum/listpost.php';
	}
	// Liste des messages dans un langage
	elseif (isset($postIDCateg) and isset($postList)) {
		include 'forum/listpost.php';
	}
	// Langage
	elseif (isset($postIDCateg)) {
		include 'forum/listpost.php';
	}
	// Seulement une liste
	elseif (isset($postList)) {
		include 'forum/listpost.php';
	}
	// Page d'accueil
	else {
		include 'forum/defaut.php';
	}

?>

<?php include "bottom.php"; ?>
