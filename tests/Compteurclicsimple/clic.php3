<?
include("config.php3");

mysql_connect($host,$user,$pass);
mysql_select_db($base);

if (isset($id)) {
//requete pour aller chercher l'adresse du lien 
$sql_url = "SELECT adressedusite FROM compteurdeclicsimple WHERE id = '$id' "; 
//on incremente le compteur de clic 
$sql_clic = "UPDATE compteurdeclicsimple SET clic=clic+1 WHERE id = '$id' "; 
//envoi des requetes 
$sel = mysql_query($sql_url); 
$upd = mysql_query($sql_clic); 
//traitement du resultat pour l'url 
$resultat = mysql_fetch_array($sel,MYSQL_ASSOC); 
$url = $resultat[adressedusite]; 
//on renvoi le visiteur vers le site 
Header("Location:$url"); 
}
elseif (isset($adressedusite)) {
$res = mysql_query("SELECT adressedusite FROM compteurdeclic WHERE adressedusite ='$adressedusite'");
if (mysql_num_rows($res) == 0) {
mysql_query("INSERT INTO compteurdeclicsimple VALUES ('0','$adressedusite', '1')");
}
else {
mysql_query("UPDATE compteurdeclicsimple SET clic=clic+1 WHERE adressedusite='$adressedusite'");
}
Header("Location: $adressedusite");
echo "<HTML></HTML>";
}

function show_nb_clics($id) 
//affiche le nombre de clic d'un ID 
{ 
//creation de la requete 
$sql = "SELECT clic FROM compteurdeclicsimple WHERE id = '$id' "; 
//envoi de la requete 
$res = mysql_query($sql); 
//traitement du resultat 
$resultat = mysql_fetch_array($res,MYSQL_ASSOC); 
//affichages du resultat 
echo "($resultat[clic] clics)"; 
} 

?>





