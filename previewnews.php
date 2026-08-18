<?php
	// Liste les news
	if (@dbConnect() !=0) {
		$req = "SELECT `ID`, `Title`, `Text`, `Date` FROM `news` ORDER BY `ID` DESC LIMIT 2";
		$result = mysql_query($req);
		if (mysql_num_rows($result) == 0) {
				echo 'Aucune news pour le moment.';
		}
		while ($rs = mysql_fetch_row($result)) {
			$newsID_tmp = $rs[0];
			$newsTitle_tmp = $rs[1];
			$newsText_tmp = $rs[2];
			$newsDate_tmp = FormatDate($rs[3]);
			// On garde que les 2 premières lignes du texte
			$newsText_tmp = str_replace('<br>', '<br />', $newsText_tmp);
			$br_pos = strpos($newsText_tmp, '<br />');
			$newsText_tmp1='';
			if ($br_pos) $newsText_tmp1 = substr($newsText_tmp, 0, $br_pos) . '<br />';
			if ($br_pos) $newsText_tmp = substr($newsText_tmp, $br_pos + strlen('<br />'));
			$br_pos = strpos($newsText_tmp, '<br />');
			if ($br_pos) $newsText_tmp1 .= substr($newsText_tmp, 0, $br_pos);
			?>
				<div class="PreviewNews">
				<h3 class="previewNews">
					<img src="images/previewnews.gif" border="0" width="20" height="20" alt="" align="middle">
					<span class="PreviewNews_Date"><?php echo $newsDate_tmp; ?></span>
					<a href="news.php#news<?php echo $newsID_tmp; ?>">
						<?php echo $newsTitle_tmp; ?>
					</a>
				</h3>
				<p class="PreviewNews">
					<?php echo $newsText_tmp1; ?>... &nbsp; &nbsp;
					<a href="news.php#news<?php echo $newsID_tmp; ?>">Lire la suite</a>
				</p>
				</div>
			<?php
		}
		dbClose();
	}
?>
