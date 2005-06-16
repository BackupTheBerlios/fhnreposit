<?
if (!isset($AppIncl)) {
require("infos.php");	
InitPage(true,"Fiche personne"); // initialise en envoyant les balises de début <HTML> etc ...
}
// fait la diff entre création et modif

switch ($ss_prmev[ro]) {
	case "M":
		if ($TE=="A") {
			$TypEdit="";
			$TypEdittxt="AJOUT";
			}
		else
			{
			$TypEdit=1;
			$TypEdittxt="MISE A JOUR";
			}
		break;
	
	case "":
	// sécurité: en fait si rien ds ss_prmev, repasse en consultation
	case "C":
	default:
		$TypEdit="C";
		break;
}  

if (isset($AppIncl)) $TypEdittxt="";
	
$where=($TypEdit!="" ? "where PEX_NUPERS='$NUPERS'" :"where 1 LIMIT 1");

if ($ss_prmev[typers]=="E") {
	$from="PERS_EXT LEFT JOIN SOCIETE ON PEX_COSOCIE=SOC_NUSOCIE";
	$select="PERS_EXT.*,SOCIETE.*";
	}
else
	{ $from="PERS_EXT LEFT JOIN INFOS_DRH ON PEX_NUPERS=DRH_NUPERSO";
	  $select="PERS_EXT.*,INFOS_DRH.*";
	}

if ($debug) echo ("requete: SELECT $select FROM $from $where<br>\n");

$req=msq("SELECT $select FROM $from $where");
?>

<script language="Javascript">
function ConfReset() {
         if (confirm('Etes vous certain de vouloir remettre toutes les champs à leurs valeurs par défaut ou d\'origine ?')) document.theform.reset();
}

// Javascript qui met à jour l'Id, le mail et le N° SDL7 en fonction du nom premon
// appelé qd nouvel enregistrement sur  chgt de prenom ou de nom

<? if ($TypEdit=="") {echo "var NEWP=true;";} else echo "var NEWP=false;";
echo "\n";
if ($ss_prmev[typers]=="E") {echo "var EXT=true;";} else echo "var EXT=false;";
echo "\n";
?>
	
function MAJIdMailSDL7() {
	if (NEWP) {
		 document.theform.PEX_LCIDPERS.value =  document.theform.PEX_LLPRENOMPERS.value.toUpperCase().substring(0,1) +  document.theform.PEX_LLNOMPERS.value.toUpperCase().substring(0,5);
		if (EXT) { 
			 document.theform.PEX_MAILPERS.value = document.theform.PEX_LLPRENOMPERS.value.toLowerCase()+ '.' + document.theform.PEX_LLNOMPERS.value.toLowerCase(); 
			} 
		}
	}  
<? // appel d'un javascript redimensionnant la fenêtre en largeur 
 if (!isset($AppIncl)) { ?>
 	window.resizeTo(500,500);
<? } ?>
</script>

<form action="amact_pext.php" method="post" name="theform" ENCTYPE="multipart/form-data"> 
<input type="hidden" name="modif" value="<?=$TypEdit?>">
<input type="hidden" name="PEX_NUPERS" value="<?=$NUPERS?>">
<input type="hidden" name="PEX_LMTYPERS" value="<?=($ss_prmev[typers]=="E" ? "EXT" : "INT")?>">

<div align="center">
<a name="haut"></a>
<table width="450" border="0"> 
<tr><td align="center" colspan="2" width="450" >
<span class="TRM"><?=$TypEdittxt?></span>
<? if (!isset($AppIncl)) {  // Boutons fermer et valider en haut pour facilité de MAJ, que quand vraie popup, pas appelée par la liste ?>
	<?=nbsp(10);?><a href="#" onclick="javascript:self.close()"><img src="../../intranet/partage/IMAGES/bout_fermer.gif" border="0" alt="<?=($ss_prmev[ro]!="C" ? "Annuler tous les changement et ":"")?>fermer cette fenêtre"></A>
	<? // boutons valider que quand read only false
	    if ($ss_prmev[ro]!="C") { ?>
	        <?=nbsp(5);?><INPUT TYPE="image" src="../../intranet/partage/IMAGES/valider.gif" border="0" onmouseover="self.status='Valider';return true">
	<? }
	EchoTitIm1("FICHE PERSONNE EXTERNE");
	} // fin si pas appelé par liste?>

</td></tr>
<?
  InitObjsReq($req,$TypEdit); // appelle fonction qui initialise autant d'objets PYA qu'il y a
  // de champs ds la requête, tenant compte automatiquement de leur table d'appartenance  	  
	echo "<tr><td colspan=\"2\" align=\"center\"><span class=\"chapitrered12px\">";
	
	if (isset($AppIncl)) $CIL[PEX_LMTITREPER]->Fccr="yes";

	echo ($CIL[PEX_LMTITREPER]->TypEdit!="C" ? "Titre:&nbsp;" :"");
	$CIL[PEX_LMTITREPER]->EchoEditAll();
	$CIL[PEX_LLPRENOMPERS]->Tt_PdtMaj="onChange:MAJIdMailSDL7();";
	echo ($CIL[PEX_LLPRENOMPERS]->TypEdit!="C" ? " Prénom:&nbsp;" :" ");
	$CIL[PEX_LLPRENOMPERS]->EchoEditAll();
	echo ($CIL[PEX_LLNOMPERS]->TypEdit!="C" ? "<BR>Nom:&nbsp;" :" ");
	$CIL[PEX_LLNOMPERS]->Tt_PdtMaj="onChange:MAJIdMailSDL7();";
	$CIL[PEX_LLNOMPERS]->EchoEditAll();

	echo "</span>";
	if ($ss_InfoUser[COPROFIL]=="SADMIN") echo "<span class=\"legendes9px\">(".$CIL[PEX_NUPERS]->ValChp.")</span>";
	echo "<br>\n";
	echo ($TypEdit!="C" ? "Photo : " :""); 
	$CIL[PEX_PHOTO]->EchoEditAll();
	echo "</td></tr>\n";
	echo "<tr><td width=\"220\" class=\"backredc\"><b>Coordonnées</b></td><td width=\"220\" >&nbsp;</td></tr>\n";
	EchoLig("PEX_TELFIXE");
	EchoLig("PEX_LCABREGE");
	EchoLig("PEX_FAX");
	EchoLig("PEX_PORPERS");
	
	if ($TypEdit=="C") { // en consult, n'affiche le mail que s'il est actif et non vide
		if ($CIL[PEX_TOPMAIL]->ValChp=="O" && $CIL[PEX_MAILPERS]->ValChp!="") EchoLig("PEX_MAILPERS");
		}
	else 
		{ // en modif, affiche le mail et son activation éditable seulement pour les externes
		// seule l'admin de mail peut le changer pour les internes
		// ceci est réalisé sur une autre page
		$FTE=($ss_prmev[typers]=="E" ? $TypEdit : "C");
		EchoLig("PEX_TOPMAIL",$FTE);
		EchoLig("PEX_MAILPERS",$FTE);
		EchoLig("PEX_EXPEXT",$FTE);
		EchoLig("PEX_LCIDPERS",$FTE);
		if ($FTE=="C") { // en modif interne
			echo "<tr><td colspan=\"2\" class=\"legendered9px\">";
			echo "<U>N.B.:</U> Seul le(s) administrateur(s) de mail <b>";
			$rqrm=msq("select PEX_LLPRENOMPERS,PEX_LLNOMPERS,PEX_MAILPERS from PERS_EXT LEFT JOIN ENV_POSSEDE ON PEX_NUPERS=POS_NUPERS WHERE POS_COPROFIL='ML_ADM' OR POS_COPROFIL='SADMIN'");
			while ($rwrm=mysql_fetch_array($rqrm)) {
				echo "<a href=\"mailto:".$rwrm[PEX_MAILPERS]."?subject=Activation/modification de mail\">".$rwrm[PEX_LLPRENOMPERS]." ".$rwrm[PEX_LLNOMPERS]."</A>, ";
				}
			echo "</b>sont habilités à gérer les adresses mails internes ainsi que l'identifiant et l'exportation des coordonnées. Contactez-les pour ce faire.</td></tr>";
			}
 		}
	
	//liste rouge qu'en édition
	
	if ($TypEdit!="C") EchoLig("PEX_REDLIST");

	// affichage structure de rattachement
	if ($TypEdit!="C") { // Liste déroulante en edition
		// Ici traiter le cas des adm UF, avec limitation aux structures gérées par les UF dépendantes
		EchoLig("PEX_COSOCIE");
		}
	else { // normal avec popup en consult 
		echo "<tr><td>structure</td><td>";
		echo "<a class=\"boldred11px\" href=\"popup_fichste.php?COSOCIE=".$CIL[PEX_COSOCIE]->ValChp."\">";
		$CIL[PEX_COSOCIE]->EchoEditAll();
		echo "</a></td></tr>";
		} // fin consultation
	EchoLig ("PEX_FONCTION");
	EchoLig ("PEX_COMMENT");
		
	// affiche les infos sur le user maj, date MAJ précédent en modif/creation uniquement
	// et pas si liste des fiches detaillées
	if ($TypEdit!="C" && !isset($AppIncl)) {
		EchoLig("PEX_DTCREA",$TypEdit);
		EchoLig("PEX_DTMAJ",$TypEdit);
		EchoLig("PEX_COOPE",$TypEdit);
	}
?>
<tr><td colspan="2" align="center">
<? if (!isset($AppIncl)) {  // Boutons que quand vraie popup, pas appelée par la liste ?>
	<a name="bas"><br>
	<a href="#" onclick="javascript:self.close()"><img src="../../intranet/partage/IMAGES/bout_fermer.gif" border="0" alt="<?=($ss_prmev[ro]!="C" ? "Annuler tous les changement et ":"")?>fermer cette fenêtre"></A>
	<? // boutons valider et annuler que quand read only false
	    if ($ss_prmev[ro]!="C") { ?>
	        &nbsp;&nbsp;&nbsp;&nbsp;<A HREF="javascript:ConfReset()" title="RAZ du formulaire"><IMG src="../../intranet/partage/IMAGES/annuler.gif" border="0"></a>
	        &nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE="image" src="../../intranet/partage/IMAGES/valider.gif" border="0" onmouseover="self.status='Valider';return true">
	    <?} ?>
	        &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="javascript:self.print()"><img src="../../intranet/partage/IMAGES/imprimer.gif" border="0" alt="Imprimer cette fiche"></A>
<? } // fin si pas appelé par liste ?>
</td></tr></table>
</div>
</BODY>
</HTML>

