	<!-- Le pied de page -->
	<div align="center" style="font-size: 10px; font-weight: normal; color: black">
		<br />
		<p>
    		<a href="http://validator.w3.org/check?uri=referer"><img
        		src="images/valid-html401.png"
        		alt="Valid HTML 4.01 Transitional" style="border:0;width:88px;height:31px"></a>
 			<a href="http://jigsaw.w3.org/css-validator/">
  				<img style="border:0;width:88px;height:31px"
       				src="images/vcss.png" 
       				alt="Valid CSS" />
 			</a>
		</p>
		Site web de Vb System Library version 1.3<br />
		Developpement et design réalisé par : <b><a title="Site personnel de matthieu napoli" href="http://www.mnapoli.fr/">Matthieu Napoli</a>
		(<a href="user.php?ID=1">MadMatt</a>)</b><br />
		© 2007 Toute reproduction même partielle est interdite sauf accord écrit du Webmaster<br />
		<?
			// Temps d'exécution de la page
			list($usec, $sec) = explode(" ", microtime());
	    	$new_microtime=(float)$usec + (float)$sec;
			printf("Temps d'execution de la page : %2.3f s<br />", $new_microtime-$start_time);
		?>
		<a title="Site personnel de matthieu napoli" href="http://www.mnapoli.fr/">www.mnapoli.fr</a>
	</div>
</div>

<!-- Menu -->
<div id="Menu">
	<?php
		include("menu.php");
	?>
</div>

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1822687-1";
urchinTracker();
</script>

</body>
</html>

