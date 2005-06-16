<?
require("infos.php");	
InitPage(true,"Fiche Unité fonctionnelle"); // initialise en envoyant les balises de début <HTML> etc

if (!isset($TypEdit)) $TypEdit="C";

// $TypEditRP est le type d'"dition Réduit par profil
// tous les champs affectés de réduit par profil ne seront évntuellement pas éditable même en édition	
if ($ss_InfoUser[COPROFIL]!="DRH_ADM" && $ss_InfoUser[COPROFIL]!="SADMIN")
	{$TypEditRP="C";}
	else $TypEditRP=$TypEdit;
 
?>
<script language="Javascript">
function ConfReset() {
         if (confirm('Etes vous certain de vouloir remettre toutes les champs à leurs valeurs par défaut ou d\'origine ?')) document.theform.reset();
}
window.resizeTo(480,500);
</script>

<a name="haut"></a>
<form action="amact_UF.php" method="post" name="theform" ENCTYPE="multipart/form-data"> 
<input type="hidden" name="modif" value="<?=$TypEdit?>">
<input type="hidden" name="UFO_NUUNITE" value="<?=$NUUNITE?>">

<table width="450" border="0">
<tr><td align="center" colspan="2" width="450">
<? EchoTitIm1("FICHE Unité fonctionnelle");?>
</td></tr>
<?

  $req=msq("SELECT * FROM UNITE_FONCTION where UFO_NUUNITE='$NUUNITE'");
  InitObjsReq($req,$TypEdit); // initialise autant d'objets que de champs dans la requête;
  	$CIL[UFO_LLUNITE]->TypEdit=$TypEditRP; 	  
	echo "<tr><td colspan=\"2\" class=\"chapitrered12px\" align=\"center\">";
	echo ($TypEditRP!="C" ? "Libellé " : ""); 
	$CIL[UFO_LLUNITE]->EchoEditAll();
	echo "</td></tr>\n";
	EchoLig("UFO_FICHASS");
  	$CIL[UFO_COUFOSUP]->TypEdit=$TypEditRP; 	  
	EchoLig("UFO_COUFOSUP");
	EchoLig("UFO_TELUNITE");
	EchoLig("UFO_FAXUNITE");
	EchoLig("UFO_PORUNITE");
	EchoLig("UFO_LCABREGE");
	EchoLig("UFO_MAILUNITE");
	echo "<tr><td align=\"top\">Coordonnées postales</td><td>";
	$CIL[UFO_LLADRES]->EchoEditAll();
	if ($CIL[UFO_LLADRES2]->ValChp!="" || $TypEdit!="C") {
		echo "<BR>";
		$CIL[UFO_LLADRES2]->EchoEditAll();}
	echo "<BR>";
	$CIL[UFO_COPOSTAL]->EchoEditAll();
	echo " ";
	$CIL[UFO_LLCOMMU]->EchoEditAll();
	echo "</td></tr>\n"; // fin adresse
	EchoLig("UFO_COPAYS");
	$CIL[UFO_COHIERA]->TypEdit=$TypEditRP; 	  
	if ($TypEdit!="C") EchoLig("UFO_COHIERA");
	$wh1="where (DRH_LLACTIVITE LIKE '".str_replace(":","' OR DRH_LLACTIVITE LIKE  '",vdc(Ctbicepa(),1))."') ";
	$wh2="AND (DRH_NUUNITE LIKE '";
	foreach (TbUFdep($NUUNITE) as $NUU) {
		$wh2.=$NUU."' OR DRH_NUUNITE LIKE '";
		}
	$wh2=vdc($wh2,22).")"; // elnlève le dernier  OR DRH_NUUNITE LIKE '
	echo "<tr><td>effectif</td><td>";
	$rqsqlcnt="select COUNT(*) from INFOS_DRH ".$wh1.$wh2;
	if ($debug) echo "requête SQL de comptage: ";
	$rescnt=msq($rqsqlcnt);
	$rwcnt=mysql_fetch_row($rescnt);
	echo $rwcnt[0];
	echo "</td></tr>";
	//EchoLig("UFO_NBEFFEC");
  	$CIL[UFO_COTYUNITE]->TypEdit=$TypEditRP; 	  
	EchoLig("UFO_COTYUNITE");
	EchoLig("UFO_PLANACC");

  ?>
<tr><td colspan="2" align="center">
<a name="bas">
<br>
<a href="javascript:this.close();"><img src="../../intranet/partage/IMAGES/bout_fermer.gif" border="0" width="70" height="11" alt="Fermer cette fenêtre"></a>
<? // boutons valider et annuler que quand read only false
    if ($TypEdit!="C") { ?>
        &nbsp;&nbsp;&nbsp;&nbsp;<A HREF="javascript:ConfReset()" title="RAZ du formulaire"><IMG src="../../intranet/partage/IMAGES/annuler.gif" border="0"></a>
        &nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE="image" src="../../intranet/partage/IMAGES/valider.gif" border="0" onmouseover="self.status='Valider';return true">
    <?} ?>
        &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="javascript:self.print()"><img src="../../intranet/partage/IMAGES/imprimer.gif" border="0" alt="Imprimer cette fiche"></A>
</td></tr></table>
</BODY>
</HTML>

