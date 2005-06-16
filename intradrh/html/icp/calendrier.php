<?
	require("../infos.php");	
	InitPage(true,"Calendrier","../"); // initialise
	$llicp=RecupLib("ICP","ICP_COICP","ICP_LLICP",$coicp);
?>
<table width="300" border="0">
<tr><td align="center" colspan="2">
<span class="TRM"><?=$llicp?></span>
<? EchoTitIm1("CALENDRIER"); ?>
<br><br>
<img src="../../images/travaux_icp.jpg" align="center" border="0" width="300" height="205" alt="">
<br><br>
Les reponsables décident du calendrier des <b><?=$llicp?> </b>et leurs montures les attendent patiemment ...
<br><br>
En fait, désolé, mais cette page est actuellement en cours de réalisation ...
<br><br>
</td></tr></table>
</div>
</body>
</html>
