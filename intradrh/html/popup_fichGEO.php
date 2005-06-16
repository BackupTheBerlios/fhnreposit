<?
require("infos.php");	
InitPage(true,"Fiche position géographique"); // initialise en envoyant les balises de début <HTML> etc

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
<form action="amact_GEO.php" method="post" name="theform" ENCTYPE="multipart/form-data"> 
<input type="hidden" name="modif" value="<?=$TypEdit?>">
<input type="hidden" name="GEO_NUPOSIT" value="<?=$NUUNITE?>">

<table width="450" border="0">
<tr><td align="center" colspan="2" width="450">
<? EchoTitIm1("FICHE Position géographique");?>
</td></tr>
<?

/*
Structure de cette table
GEO_POSIT (Positions géographiques Liste des entités ou se trouvent du personnel HN)
GEO_NUPOSIT, de type int(11) Type aff.: HID
GEO_LLPOSIT, de type varchar(50) - Libellé Type aff.: AUT
GEO_TELPOSIT, de type varchar(20) - Téléphone Type aff.: AUT
GEO_FAXPOSIT, de type varchar(20) - Fax Type aff.: AUT
GEO_PORPOSIT, de type varchar(20) - Tel Mobile Type aff.: AUT
GEO_LCABREGE, de type varchar(4) - N° Tel abrégé Type aff.: AUT
GEO_MAILUNITE, de type varchar(100) - Email Type aff.: AUT
GEO_LLADRES, de type varchar(40) - Adresse Type aff.: AUT
GEO_LLADRES2, de type varchar(40) - Complément d\'adresse Type aff.: AUT
GEO_COPOSTAL, de type varchar(20) - Code postal Type aff.: AUT
GEO_LLCOMMU, de type varchar(40) - Ville Type aff.: AUT
GEO_COPAYS, de type varchar(20) - Pays Type aff.: AUT
GEO_NBEFFEC, de type smallint(6) - Effectif Type aff.: AUT
GEO_COUFORAT, de type varchar(20) - Unité fonctionnelle de rattachement Type aff.: LDL Valeurs: UNITE_FONCTION,UFO_NUUNITE,UFO_LLUNITE
GEO_FICHASS, de type varchar(255) - Fichier associé Type aff.: FICFOT Valeurs: ../drh_photo_GEO/
GEO_PLANACC, de type varchar(255) - Plan d\'accès Type aff.: FICFOT Valeurs: ../drh_planacc_GEO/
GEO_POSX, de type float - Position X Type aff.: AUT
GEO_POSY, de type float - Position Y Type aff.: AUT
GEO_DTCREA, de type date - Date de création Type aff.: STA
GEO_DTMAJ, de type date - Date de mise à jour Type aff.: STA
GEO_COOPE, de type smallint(6) - Personne ayant effectué la MAJ Type aff.: AUT Valeurs: PERSONNE,PER_NUPERS,PER_LMTITREPER, !PER_LLPRENOMPERS, !@PER_LLNOMPERS
*/
  $req=msq("SELECT * FROM GEO_POSIT where GEO_NUPOSIT='$NUUNITE'");
  InitObjsReq($req,$TypEdit); // initialise autant d'objets que de champs dans la requête;
  	$CIL[GEO_LLPOSIT]->TypEdit=$TypEditRP; 	  
	echo "<tr><td colspan=\"2\" class=\"chapitrered12px\" align=\"center\">";
	echo ($TypEditRP!="C" ? "Libellé " : ""); 
	$CIL[GEO_LLPOSIT]->EchoEditAll();
	echo "</td></tr>\n";
	EchoLig("GEO_FICHASS");
  	$CIL[UFO_COUFOSUP]->TypEdit=$TypEditRP; 	  
	EchoLig("GEO_COUFORAT");
	EchoLig("GEO_TELPOSIT");
	EchoLig("GEO_FAXPOSIT");
	EchoLig("GEO_PORPOSIT");
	EchoLig("GEO_LCABREGE");
	EchoLig("GEO_MAILUNITE");
	echo "<tr><td align=\"top\">Coordonnées postales</td><td>";
	$CIL[GEO_LLADRES]->EchoEditAll();
	if ($CIL[UFO_LLADRES2]->ValChp!="" || $TypEdit!="C") {
		echo "<BR>";
		$CIL[GEO_LLADRES2]->EchoEditAll();}
	echo "<BR>";
	$CIL[GEO_COPOSTAL]->EchoEditAll();
	echo " ";
	$CIL[GEO_LLCOMMU]->EchoEditAll();
	echo "</td></tr>\n"; // fin adresse
	EchoLig("GEO_COPAYS");
	if ($TypEdit!="N") {
		$wh1="where (DRH_LLACTIVITE LIKE '".str_replace(":","' OR DRH_LLACTIVITE LIKE  '",vdc(Ctbicepa(),1))."') ";
		$wh2="AND DRH_NUGEOPOS=".$NUUNITE;
		echo "<tr><td>effectif</td><td>";
		$rqsqlcnt="select COUNT(*) from INFOS_DRH ".$wh1.$wh2;
		if ($debug) echo "requête SQL de comptage: ";
		$rescnt=msq($rqsqlcnt);
		$rwcnt=mysql_fetch_row($rescnt);
		echo $rwcnt[0];
		echo "</td></tr>";
	}
	EchoLig("GEO_PLANACC");

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

