<?php

// Fichier de configuration
include('passwords/pass.php');
include('functions/functions_users.php');
include('functions/functions_codes.php');
include('functions/functions_commentaires.php');
include('functions/functions_mails.php');
include('functions/functions_versions.php');

$IsConnected = 0;

// Connexion à la base de données
// Retourne l'ID de connexion
function dbConnect()
{
	global $dbServer, $dbUser, $dbPassword, $dbBase;
	global $IsConnected;
	// Si on est pas déjà connecté
	if ($IsConnected == 0) {
		$dbIDConnexion = mysql_connect($dbServer, $dbUser, $dbPassword);
		if ($dbIDConnexion != 0) {
			$dbConnexionResult = mysql_select_db($dbBase);
			if ($dbConnexionResult == 0) {
				Error('Impossible de se connecter à la base de données. Le problème est surement temporaire, réssayez plus tard.');
				$IsConnected = 0;
				return 0;
			}
			else {
				$IsConnected = 1;
				return 1;
			}
		}
		else {
			Error('Impossible de se connecter à la base de données. Le problème est surement temporaire, réssayez plus tard.');
			$IsConnected = 0;
			return 0;
		}
	}
	else {
		$IsConnected = 1;
		return 1;
	}
}

// Ferme la connexion à la base de données
function dbClose()
{
	global $IsConnected;
	if ($IsConnected == 1) {
		$IsConnected = 0;
		mysql_close();
	}
}

// Affiche la page d'erreur avec le message
function Error($errorDescr, $url='')
{
	if ($url == '') {
		$url = $_SERVER['PHP_SELF'];
	}
	if ($errorDescr != '') {
		$end = '&Descr=' . $errorDescr;
	}
	else {
		$end = '';
	}
	Redirect('error.php?url=' . $url . $end);
	Die('Une erreur est survenue. Si vous n\'êtes pas redirigé automatiquement, cliquez sur le lien :
	<a href="error.php?url=' . $url . $end . '">Informations sur l\'erreur</a>.');
}

// Renvoie le nom du niveau correspondant
function GetRightsName($rights)
{
	if ($rights==1) { $namerights = "Administrateur"; }
	if ($rights==2) { $namerights = "Membre"; }
	return $namerights;
}

// Renvoie le nom du tri correspondant
function GetTriName($tri)
{
	if ($tri == 'a') { $nametri = "Tri alphabétique"; }
	if ($tri == 'b') { $nametri = "Du plus récent au plus ancien"; }
	if ($tri == 'c') { $nametri = "Du plus ancien au plus récent"; }
	return $nametri;
}


// Formate une date en xx/xx/xxxx
function FormatDate($timestamp)
{
	$date=substr($timestamp,8,2).'/'.substr($timestamp,5,2).'/'.substr($timestamp,0,4);
	return $date;
}

// Formate une date en jj-mm-aaaa
function FormatDateDiff($timestamp)
{
	$date=substr($timestamp,8,2).'-'.substr($timestamp,5,2).'-'.substr($timestamp,0,4);
	return $date;
}

// Formate une durée en xxHxxMin
function FormatDuration($time)
{
	$duration = substr($time, 0, 2) . 'H' . substr($time, 3, 2) . 'Min';
	return $duration;
}

// Formate un url pour avoir http:// devant
function FormatURL($url)
{
	if ($url == 'http://') { return ''; }
	if ((substr($url, 0, 7) != 'http://') and ($url != '') and ($url != 'http://')) { $url = 'http://' . $url; }
	return $url;
}

// Enlève les scipts d'une chaine
function RemoveScriptstr($str)
{
	$str = str_replace(array('<script','</script>','<?','?>'), array('&lt;script','&lt;/script&gt;','&lt;?','?&gt;'), $str);
	return $str;
}

// Formate le texte
function FormatText($str)
{
	$str = trim($str);
	$str = htmlentities($str);
	$str = nl2br($str);
	if (get_magic_quotes_gpc()) {
	   $str = $str;
	}
	else {
	   $str = addslashes($str);
	}
	return $str;
}

// Déformate le texte : transforme le code html en caractères normaux
function DeformatText($str)
{
	$str = html_entity_decode($str);
	return $str;
}

// Formate un code
function FormatCode($str)
{
	$str = trim($str);
	$str = htmlentities($str);
	$str = str_replace('  ', '&nbsp; ', $str);
	$str = nl2br($str);
	if (get_magic_quotes_gpc()) {
	   $str = $str;
	}
	else {
	   $str = addslashes($str);
	}
	return $str;
}

// Inverse de nl2br : <br /> donne un retour à la ligne
function InverseNl2br($str)
{
	$str = str_replace('<br />', '', $str);
	return $str;
}

// Supprime les accents
function DelAccents($chaine){
	$tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	return(strtr($chaine, $tofind, $replac));
}

// Calcule la différence entre 2 dates
// Format des dates : 'dd-mm-yyyy'
// L'unité du résultat est 'd'=jours ou 'y'=années
function DateDiff($date_from, $date_to, $unit='d')
{
   // Récupère les parties des dates
   $date_from_parts = explode('-', $date_from);
   $date_to_parts = explode('-', $date_to);
   $day_from = $date_from_parts[0];
   $mon_from = $date_from_parts[1];
   $year_from = $date_from_parts[2];
   $day_to = $date_to_parts[0];
   $mon_to = $date_to_parts[1];
   $year_to = $date_to_parts[2];

   // Si la différence est négative on gère le signe
   $sign=1;
   if ($year_from>$year_to) $sign=-1;
   else if ($year_from==$year_to)
       {
       if ($mon_from>$mon_to) $sign=-1;
       else if ($mon_from==$mon_to)
           if ($day_from>$day_to) $sign=-1;
       }

   if ($sign==-1) {	// Gestion du signe
       $day_from = $date_to_parts[0];
       $mon_from = $date_to_parts[1];
       $year_from = $date_to_parts[2];
       $day_to = $date_from_parts[0];
       $mon_to = $date_from_parts[1];
       $year_to = $date_from_parts[2];
       }

   switch ($unit)
       {
       case 'd': case 'D':		// calcule la différence en jours
       $yearfrom1=$year_from;  //actual years
       $yearto1=$year_to;      //(yearfrom2 and yearto2 are used to calculate inside the range "0")  
       //checks ini date
       if ($yearfrom1<1980)
           {//year is under range 0
           $deltafrom=-floor((1999-$yearfrom1)/20)*20; //delta t1
           $yearfrom2=$yearfrom1-$deltafrom;          //year used for calculations
           }
       else if($yearfrom1>1999)
           {//year is over range 0
           $deltafrom=floor(($yearfrom1-1980)/20)*20; //delta t1
           $yearfrom2=$yearfrom1-$deltafrom;          //year used for calculations          
           }
       else {//year is in range 0
           $deltafrom=0;
           $yearfrom2=$yearfrom1;
           }
          
       //checks end date
       if ($yearto1<1980) {//year is under range 0
           $deltato=-floor((1999-$yearto1)/20)*20; //delta t2
           $yearto2=$yearto1-$deltato;            //year used for calculations
           }
       else if($yearto1>1999) {//year is over range 0
           $deltato=floor(($yearto1-1980)/20)*20; //delta t2
           $yearto2=$yearto1-$deltato;            //year used for calculations          
           }
       else {//year is in range 0
           $deltato=0;
           $yearto2=$yearto1;
           }
  
       //Calculates the UNIX Timestamp for both dates (inside range 0)
       $ts_from = mktime(0, 0, 0, $mon_from, $day_from, $yearfrom2);
       $ts_to = mktime(0, 0, 0, $mon_to, $day_to, $yearto2);
       $diff = ($ts_to-$ts_from)/86400;
       //adjust ranges
       $diff += 7305 * (($deltato-$deltafrom) / 20);
       return $sign*$diff;
       break;
      
       case 'y': case 'Y': //calculates difference in years
       $diff=$year_to-$year_from;      
       $adjust=0;
       if ($mon_from>$mon_to) $adjust=-1;
       else if ($mon_from==$mon_to)
           if ($day_from>$day_to) $adjust=-1;
      
       return $sign*($diff+$adjust);
       break;      
       }
}

// Permet d'effacer un répertoire
function Effacer($fichier)
{
	if (file_exists($fichier)) {
		chmod($fichier,0777);
		if (is_dir($fichier)) {
			$id_dossier = opendir($fichier);
			while($element = readdir($id_dossier)) {
				if ($element != "." && $element != "..")
					effacer($fichier."/".$element);
			}
			closedir($id_dossier);
			rmdir($fichier);
		}
		else unlink($fichier);
	}
}

/* Permet de créer un répertoire
	Exemple : Créer c:/test/essai/
	Si le dossier test n'existe pas il sera créé en plus du dossier essai*/
function MakeDir($dir)
{
	// On vérifie qu'il y ait bien un dossier
	if (($dir == '') or ($dir == '/') or (is_dir($dir))) {
		return 0;
	}
	// Si c'est un dossier simple on le crée
	if (strrpos($dir, '/') == false) {
		mkdir($dir);
		return 0;
	}
	$pos_ini = 0;
	$dir_depart = '';
	while($dir) {
		// Trouve le dossier au début de $dir
		$dossier = '';
		$pos_ini = 0;
		$t = $pos_ini;
		while((strrpos($dossier, '/') == false) and ($t <= strlen($dir))) {
			$t += 1;
			$dossier = substr($dir, $pos_ini, $t);
		}
		if (!is_dir($dir_depart . $dossier)) {
			mkdir($dir_depart . $dossier);
		}
		$dir = substr($dir, $t, strlen($dir));
		$dir_depart .= $dossier;
	}
}

// Crée un messagebox
function MsgBox($message)
{
	echo '<script language="Javascript">
				alert("' . $message . '");
				</script>';
}

// Fait un Redirect
function Redirect($page)
{
	echo "<script language='Javascript'>
				window.location='$page';
				</script>";
}

// Récupérer l'adresse IP du visiteur
function GetIP()
{
	if(isset($_SERVER)) {
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		elseif(isset($_SERVER['HTTP_CLIENT_IP']))
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		else
			$ip = $_SERVER['REMOTE_ADDR'];
	}
	else {
		if(getenv('HTTP_X_FORWARDED_FOR'))
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		elseif(getenv('HTTP_CLIENT_IP'))
			$ip = getenv('HTTP_CLIENT_IP');
		else
			$ip = getenv('REMOTE_ADDR');
	}
	return $ip;
}

// Récupérer les dimensions pour une miniature
// sizeMax : taille du plus grand coté
function GetThumbDim($File, $sizeMax)
{
 	@list($width, $height, $type, $attr) = getimagesize($File);
	//calcul le rapport entre largeur et longueur...
	@$ratio = $width / $height;
	//test si image en portrait ou en paysage
	if ( $width >= $height ) {
		$newwidth = $sizeMax;
		@$newheight = $newwidth / $ratio;
	}
	else {
		$newheight = $sizeMax;
		$newwidth = $newheight * $ratio;
	}
	return array(intval($newwidth),intval($newheight));
}

// Enregistre une erreur dans un fichier
// Ajoute le message Msg à la fin du fichier et ajoute un retour à la ligne
function LogError($Msg, $File){
	// Si le fichier n'existe pas
	if ( !file_exists( $File ) ) {
		// on l'ouvre en mode 'création'
		$mode = 'x+';
		$Msg = $Msg . "\r\n";
	}
	// Si il existe
	else {
		//on l'ouvre en mode 'écriture'
		$mode = 'a+';
		//On récupère le contenu du fichier
		//$FileContent = file_get_contents( $File );
		$Msg = $Msg . "\r\n";
	}
	//On ouvre le fichier avec le mode choisi
	$fp = fopen( $File , $mode );
	fwrite( $fp , $Msg );
	//On ferme le fichier
	fclose( $fp );
}

// Formatage du nom de page pour XITI
// Testé avec PHP 4.3.3
Function XitiFormat($nompage) {
     $nompage = strtolower($nompage);
     $nompage = strtr($nompage,"àâäáîïíôöóùûüéèêëçñ","aaaaiiiooouuueeeecn");
     $nompage = eregi_replace("[^a-z0-9_:~\\\/\-]","_",$nompage);
     return($nompage);
}

?>
