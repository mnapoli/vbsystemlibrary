<?php

// Crée des images miniatures
// Nécessite la librairie "php_gd2"
/*utilisation de la fonction :
$path = chemin d'accès au dossier contenant la photo
$fichierSource = nom de la photo
$grand = taille du plus grand coté (hauteur pour les portraits, largeur pour les paysages)
$destination = dossier de destination de la photo, par rapport à l'emplacement de la fonction */
function thumb($path, $fichierSource, $grand, $destination)
{
	$ombre=$grand / 20;
	//teste le format de l'image et crée l'image concerné
	$image_size=getimagesize($path.$fichierSource );
	switch ( $image_size[2] ) {
		case 1 :
			$source = ImageCreateFromGif($path.$fichierSource);
			$mime_photo='image/gif';
		break;
		case 2 :
			$source = ImageCreateFromJPEG($path.$fichierSource);
			$mime_photo='image/jpeg';
		break;
		case 3 :
			$source = ImageCreateFromPNG($path.$fichierSource);
			$mime_photo='image/png';
		break;
		default:
			return 0;
	}
	
	$largeurSource = imagesx($source);
	$hauteurSource = imagesy($source);
	
	//calcul le rapport entre largeur et longueur...
	$rapport_dim= $largeurSource / $hauteurSource;

	//test si image en portrait ou en paysage
	if ( $largeurSource >= $hauteurSource ) {
		$largeurDestination = $grand;
		$hauteurDestination = $largeurDestination / $rapport_dim;
	}
	else {
		$hauteurDestination = $grand;
		$largeurDestination = $hauteurDestination * $rapport_dim;
	}

	//crée l'image (taille de l'imange source + taille de l'ombre)
	$im = ImageCreateTrueColor ($largeurDestination, $hauteurDestination);

	//rempli le fond de blanc
	$blanc=ImageColorAllocate ($im, 255, 255, 255);
	ImageFill($im, 0, 0, $blanc);
	ImageColorTransparent ($im, $blanc);

	//ajoute par dessus l'image source miniaturisée
	ImageCopyResampled($im, $source, 0, 0, 0, 0, $largeurDestination, $hauteurDestination, $largeurSource, $hauteurSource);

	//crée la miniature
	switch ( $mime_photo) {
		case 'image/jpeg' :
			ImageJpeg ($im, $destination.'/'.$fichierSource);
		break;
		case 'image/gif' :
			ImageGif ($im, $destination.'/'.$fichierSource);
		break;
		case 'image/png' :
			ImagePng ($im, $destination.'/'.$fichierSource);
		break;
	}
	return 1;
}

?>
