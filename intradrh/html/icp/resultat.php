<?
	require("../infos.php");	
	InitPage(true,"Compte-rendu / R�sultat","../"); // initialise
	$llicp=RecupLib("ICP","ICP_COICP","ICP_LLICP",$coicp);
?>
<table width="300" border="0">
<tr><td align="center" colspan="2">
<span class="TRM"><?=$llicp?></span>
<? EchoTitIm1("COMPTE-RENDU &#149; RESULTAT"); ?>
<br><br>
<img src="../../images/travaux_icp.jpg" align="center" border="0" width="300" height="205" alt="">
<br><br>
Les int�ress�s attendent avec impatience le r�sultat des <b><?=$llicp?></b>....<br><br>
En fait, d�sol�, mais cette page est actuellement en cours de r�alisation ...
<br><br>
</td></tr></table>
</div>
</body>
</html>
