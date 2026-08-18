<html>
<head>
<title>Inscription</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#CEEEFD" text="#000000"><?
require("config.php3");

if(empty($adressedusite)) //si le champ adressesite est vide alors erreur
{
   echo '<html>
<head>
<title>formulaire incomplet</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#CEEEFD" text="#000000">
<p align="center"><font color="#FF0000" face="Verdana, Arial, Helvetica, sans-serif"><b><font size="5">Formulaire 
  incomplet</font></b></font></p>
<p align="center"><b><font face="Verdana, Arial, Helvetica, sans-serif" color="#000080">Le Champ adresse du site doit être remplis</font></b></p>
<p align="center"><a href="javascript:history.back()"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Retour 
  au formulaire</font></b></a></p>
  <p align="center"><b><font face="Verdana, arialhelveticasans-serif" size="1" color="#000080">Pour 
    &ecirc;tre inform&eacute; des nouvelles versions de ce script ou pour de l\'aide</font><font size="1" color="#000080"> 
    :</font><font size="1"> <font face="Verdana, ArialHelveticasans-serif"><a href="http://www.multimania.com/webmasterfacile/Webmasters/Php/Scripts/compteurdeclicsimple.php3" target="_blank">cliquez ici</a> </font></font><p></p>';
  }
else     
  {
$db = mysql_connect("$host", "$user", "$pass");  // connexion à la base
mysql_select_db("$base",$db);


$req = mysql_query("SELECT * FROM compteurdeclicsimple WHERE adressedusite LIKE '$adressedusite%'");
$req = mysql_numrows($req);
if($req!=0) // l'url existe déjà, on affiche un message d'erreur 
{
echo "<p align=\"center\"><font face=\"Verdana, ArialHelveticasans-serif\" color=\"#FF0000\" size=\"2\">D&eacute;sol&eacute;, 
  mais cette URL <b>$adressedusite</b> existe d&eacute;j&agrave; dans votre base.</font><p></p>
<p align=\"center\"><a href=\"javascript:history.back()\"><b><font face=\"Verdana, ArialHelveticasans-serif\" size=\"1\">Retour 
  au formulaire</font></b></a></p>
  <p align=\"center\"><b><font face=\"Verdana, arialhelveticasans-serif\" size=\"1\" color=\"#000080\">Pour 
    &ecirc;tre inform&eacute; des nouvelles versions de ce script ou pour de l'aide</font><font size=\"1\" color=\"#000080\"> 
    :</font><font size=\"1\"> <font face=\"Verdana, ArialHelveticasans-serif\"><a href=\"http://www.multimania.com/webmasterfacile/Webmasters/Php/Scripts/compteurdeclicsimple.php3 \" target=\"_blank\">cliquez 
    ici</a> </font></font></b></p>";
}
else
{

                  // sélection de la base  
mysql_query("INSERT INTO compteurdeclicsimple VALUES('', '$adressedusite','0')");




echo "<div align=\"center\">
  <p><b><font face=\"Verdana, arialhelveticasans-serif\">L'URL </font></b><font face=\"Verdana, arialhelveticasans-serif\"><a href=\"$adressedusite\">$adressedusite</a></font><b><font face=\"Verdana, arialhelveticasans-serif\"> 
    vient d'&ecirc;tre ajout&eacute; dans la table </font></b></p>
  <p><b><font face=\"Verdana, arialhelveticasans-serif\" size=\"1\" color=\"#000080\">Pour 
    &ecirc;tre inform&eacute; des nouvelles versions de ce script ou pour de l'aide</font><font size=\"1\" color=\"#000080\"> 
    :</font><font size=\"1\"> <font face=\"Verdana, ArialHelveticasans-serif\"><a href=\"http://www.multimania.com/webmasterfacile/Webmasters/Php/Scripts/compteurdeclicsimple.php3 \" target=\"_blank\">cliquez 
    ici</a> </font></font></b></p>
</div>";

}
mysql_close();  // on ferme la connexion
  } 
?>
</body>

</html>
