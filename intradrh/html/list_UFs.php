<?
require("infos.php");	
InitPage(true); // initialise en envoyant les balises de d�but <HTML> etc ...

JSpopup(530,500,"popinfosDRH"); 
?>	
<table width="300" border="0">
<tr><td align="center" colspan="2">
<a name="haut"></a>
<span class="TRM"><?=($prech=="C" ? "Coordonn�es" : "MAJ")?> des Unit�s Fonctionnelles</span>
<? EchoTitIm1("RESULTAT"); 
// cherche les UF dep sf, si ADM alors ttes
$TbUf=TbUFdep(($ss_InfoUser[COPROFIL]=="DRH_ADM" || $ss_InfoUser[COPROFIL]=="SADMIN" || $prech=="C") ? "%" : 
$ss_InfoUser[NUUNITEG]);

// possibilit� de cr�ation
if ($ss_InfoUser[COPROFIL]=="DRH_ADM" || $ss_InfoUser[COPROFIL]=="SADMIN") {
$bulle="Cliquez pour cr�er une nouvelle unit� fonctionnelle";
echo "<br><a href=\"#\" onclick=\"javascript:popup('popup_fichUF.php?TypEdit=N');\" title=\"$bulle\"><span class=\"boldred11px\"><img src=\"../../../intranet/partage/IMAGES/ajouter.gif\" border=\"0\"></span></a><br/>"; }

if (count($TbUf)>=$NbLigFHB) { // affiche fl�che vers le bas 
   	?><br><a href="#bas"><img src="../../../intranet/partage/IMAGES/bas.gif" border="0" title="vers le bas et la barre de navigation"><br></a>
	<? }
	?>  
<div align="left"><br>
<? if ($prech!="C") { ?> 
Ci-dessous les unit�s fonctionnelles dont vous pouvez modifier les coordonn�es<BR>
<span class="legendes9px"><u>N.B.:</u> cette liste d�pend de votre profil...</span>
</div>
<? } else echo "Liste des unit�s fonctionnelles" ?>
<br>
</td></tr>
<?
$nolig=1;
foreach ($TbUf as $val) {
	echo "<tr><td><b>".($nolig++)." .</b></td>";
	$LLUNITE= Recuplib("UNITE_FONCTION","UFO_NUUNITE","UFO_LLUNITE",$val);
	$bulle="Cliquez pour visualiser ".($prech=="C" ? "" :"ou modifier ")."les infos d�taill�es de cette unit� fonctionnelle";  
	echo "<td><a href=\"#\" onclick=\"javascript:popup('popup_fichUF.php?NUUNITE=$val&TypEdit=".($prech=="C" ? "C" :"1")."');\" title=\"$bulle\"><span class=\"boldred11px\">";
	echo $LLUNITE."</span></td></tr>";
	}
?>
<tr><td colspan="2" align="center">
<a name="bas">

  <?
   if (count($TbUf)>=$NbLigFHB) { // affiche fl�che vers le haut, et autre bouton nouveau que si assez de lignes � afficher 
  ?> 
    <a href="#haut"><img src="../../../intranet/partage/IMAGES/haut.gif" border="0" title="Haut de la liste"></a> <? } ?>
</td></tr></table>
</BODY>
</HTML>

