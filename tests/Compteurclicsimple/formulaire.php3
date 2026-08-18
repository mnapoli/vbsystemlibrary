<html>
<head>
<title>Inscription</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#CEE7FF" text="#000000"><?
include("config.php3");

if($motdepasse != $mdp)
{
echo '
<p align="center"><font color="#FF0000" face="Verdana, Arial, Helvetica, sans-serif" size="5"><b>Mauvais 
  mot de passe</b></font></p>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="identification.php3"> 
   Identification</a>
<p></p>';
}

else
{
echo '
<head>
<title>formulaire incomplet</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

 
<form method="POST" action="add.php3" name="ajout">
  <center>
    <p><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000080">Mot 
      de passe correcte </font><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#008000">: 
      vous pouvez maintenant ajouter des url dans la base en remplissant le champ 
      ci dessous puis appuyer sur envoyer</font></b><br>
    </p><table width="75%" border="0">
      <tr> 
        <td width="50%"> 
          <div align="right"><font color="#FF0000" face="Verdana, Arial, Helvetica, sans-serif">*</font><font face="Verdana, Arial, Helvetica, sans-serif"> 
            Adresse du site à ajouter : </font></div>
        </td>
        <td width="50%"> 
          <input type="text" name="adressedusite" size="20" maxlength="100">
        </td>
      </tr>
    </table>
    <input type="submit" value="Envoyer" name="envoyer">
    <br>
  </center>
</form>

<p align="center"><b><font face="Verdana, arialhelveticasans-serif" size="1" color="#000080">Pour 
  &ecirc;tre inform&eacute; des nouvelles versions de ce script ou pour de l\'aide</font>
<font size=\"1\" color="#000080"> 
  :</font><font size="1"> <font face="Verdana, arialhelveticasans-serif"><a href="http://www.multimania.com/webmasterfacile/Webmasters/Php/Scripts/compteurdeclicsimple.php3" target="_blank">cliquez 
  ici</a> </font></font><p></p>
 ';
}

?>
</body>

</html>
