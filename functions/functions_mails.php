<?php

// Envoie un mail pour dire qu'un commentaire à été posté sur un code
function AlertSource($codeTitle, $codeID, $mail, $userName)
{
	global $SendMails;
	$sujet = 'Un nouveau commentaire a été posté sur le code ' . $codeTitle;
	$message = 'Bonjour ' . $userName . ',<br /><br />';
	$message .= 'Un nouveau commentaire a été posté sur le code :<br />';
	$message .= '<a href="code.php?ID=' . $codeID . '">' . $codeTitle . '</a><br /><br />';
	$message .= 'L\'équipe Vb System Library.<br /><br />';
	$message .= 'PS : Ceci est un mail automatique, merci de ne pas répondre.';
	$entete = "From: VbSystemLibrary\r\nContent-Type: text/html; charset=\"iso-8859-1\"\r\n";
	if ($SendMails == True) {
	   	mail($mail, $sujet, $message, $entete);
	}
}

// Envoie un mail pour dire qu'un commentaire à été posté sur un code
function AlertOwnSource($codeTitle, $codeID, $mail, $userName)
{
	global $SendMails;
	$sujet = 'Un nouveau commentaire a été posté sur votre code ' . $codeTitle;
	$message = 'Bonjour ' . $userName . ',<br /><br />';
	$message .= 'Un nouveau commentaire a été posté sur votre code :<br />';
	$message .= '<a href="code.php?ID=' . $codeID . '">' . $codeTitle . '</a><br /><br />';
	$message .= 'L\'équipe Vb System Library.<br /><br />';
	$message .= 'PS : Ceci est un mail automatique, merci de ne pas répondre.';
	$entete = "From: VbSystemLibrary\r\nContent-Type: text/html; charset=\"iso-8859-1\"\r\n";
	if ($SendMails == True) {
	   	mail($mail, $sujet, $message, $entete);
	}
}

// Envoie un mail pour dire qu'un nouveau message à été posté sur un post du forum
function AlertPost($postIDParent)
{
	global $SendMails;
	global $userID;
	// Récupère les infos du post parent
	$post = GetPostTabInfos($postIDParent);
	$user_post = GetUserTabInfos($post['iduser']);
	// Construit le mail
	$sujet = 'Une nouvelle réponse a été postée dans le forum au sujet : ' . $post['title'];
	$message = 'Bonjour,<br /><br />';
	$message .= 'Une nouvelle réponse a été postée dans le forum sur le sujet :<br />';
	$message .= '<a href="forum.php?ID=' . $postIDParent . '">' . $post['title'] . '</a><br /><br />';
	$message .= 'L\'équipe Vb System Library.<br /><br />';
	$message .= 'PS : Ceci est un mail automatique, merci de ne pas répondre.';
	$entete = "From: VbSystemLibrary\r\nContent-Type: text/html; charset=\"iso-8859-1\"\r\n";
	// Récupération des réponses
	$Replies = GetReplies($postIDParent);
	if ($Replies) {
		foreach($Replies as $key => $postID_tmp) {
			$post_tmp = GetPostTabInfos($postID_tmp);
			$user_tmp = GetUserTabInfos($post_tmp['iduser']);
			// Si ça n'est pas celui qui a posté la réponse et l'auteur du topic
			if (($user_tmp['id'] != $userID) and ($user_tmp['id'] != $post['iduser'])) {
				// S'il n'est pas déjà dans le tableau
				if (!isset($tabUsers[$user_tmp['id']])) {
					$tabUsers[$user_tmp['id']] = $user_tmp;
					if ($SendMails == True) {
	   					mail($user_tmp['mail'], $sujet, $message, $entete);
					}
				}
			}
		}
	}
	// Envoie à l'auteur du topic
	mail($user_post['mail'], $sujet, $message, $entete);
}

?>
