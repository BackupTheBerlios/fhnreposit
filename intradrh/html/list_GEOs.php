<?
require("infos.php");	
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...

JSpopup(530,500,"popinfosDRH"); 
?>	
<table width="300" border="0">
<tr><td align="center" colspan="2">
<a name="haut"></a>
<span class="TRM"><?=($prech=="C" ? "Coordonnées" : "MAJ")?> des entités géographiques</span>
<? EchoTitIm1("RESULTAT"); 
// cherche les UF dep sf, si ADM alors ttes
// gestion des droits sur les entités GEO
$TbUf=(($ss_InfoUser[COPROFIL]=="DRH_ADM" || $ss_InfoUser[COPROFIL]=="SADMIN" || $prech=="C") ? Array("%") : TbUFdep($ss_InfoUser[NUUNITEG]));

// possibilité de création
if ($ss_InfoUser[COPROFIL]=="DRH_ADM" || $ss_InfoUser[COPROFIL]=="SADMIN") {
$bulle="Cliquez pour créer une nouvelle entité géographique";
echo "<br><a href=\"#\" onclick=\"javascript:popup('popup_fichGEO.php?TypEdit=N');\" title=\"$bulle\"><span class=\"boldred11px\"><img src=\"../../../intranet/partage/IMAGES/ajouter.gif\" border=\"0\"></span></a><br/>"; }

if (count($TbUf)>=$NbLigFHB || in_array("%",$TbUf)) { // affiche flèche vers le bas 
   	?><br><a href="#bas"><img src="../../../intranet/partage/IMAGES/bas.gif" border="0" title="vers le bas et la barre de navigation"><br></a>
	<? }
 	?>
<div align="left"><br>
<? if ($prech!="C") { ?> 
Ci-dessous les entités géographiques dont vous pouvez modifier les coordonnées<BR>
<span class="legendes9px"><u>N.B.:</u> cette liste dépend de votre profil...</span>
</div>
<? } else echo "Liste des entités géographiques" ?>
<br>
</td></tr>
<?
$nolig=1;
foreach ($TbUf as $val) {
	$rb=msq("SELECT GEO_NUPOSIT,GEO_LLPOSIT from GEO_POSIT where GEO_COUFORAT LIKE '$val'");
	while($rw=mysql_fetch_row($rb)) {
		echo "<tr><td><b>".($nolig++)." .</b></td>";
		$bulle="Cliquez pour visualiser ".($prech=="C" ? "" :"ou modifier ")."les infos détaillées de cette entité géographique";  
		echo "<td><a href=\"#\" onclick=\"javascript:popup('popup_fichGEO.php?NUUNITE=".$rw[0]."&TypEdit=".($prech=="C" ? "C" :"1")."');\" title=\"$bulle\"><span class=\"boldred11px\">";
		echo $rw[1]."</span></a></td></tr>";
	}
	}
?>
<tr><td colspan="2" align="center">
<a name="bas">

  <?
   if (count($TbUf)>=$NbLigFHB) { // affiche flèche vers le haut, et autre bouton nouveau que si assez de lignes à afficher 
  ?> 
    <a href="#haut"><img src="../../../intranet/partage/IMAGES/haut.gif" border="0" title="Haut de la liste"></a> <? } ?>
</td></tr></table>
</BODY>
</HTML>

