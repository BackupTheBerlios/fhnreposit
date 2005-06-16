<?
require("infos.php");	
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...

if (isset($prech)) { // si param de recherche définis
	// valeurs par défaut 
	$ss_prmev[ro]="C"; // consultation par défaut
	$ss_prmev[typers]="E"; // trois possibilités: I (interne sans infos DRH), E (externe), P (Personnel IE avec Infos DRH)
		
	switch ($prech) {
		
		case "ME":
			$ss_prmev[title]="Mise à jour de personnes ou structures externes";
			$ss_prmev[ro]="M";
			break;
		
		case "CE" :
			$ss_prmev[title]="Consultation de personnes ou structures externes";
			break;
	
		default:
			$ss_prmev[title]="!! PAS GERE SUR CETTE PAGE !!";
		break;
	}
	
	session_register("ss_prmev");
} // fin si nvaux param de recherche définis

$TYPERS="EXT";

if ($debug) {
	DispDebug();
	echo "Cet ADM de l'UF $ss_InfoUser[NUUNITEG] gère les UF suivantes: ";
	$TbUf=TbUFdep($ss_InfoUser[NUUNITEG]);
	foreach ($TbUf as $val) {
		echo Recuplib("UNITE_FONCTION","UFO_NUUNITE","UFO_LLUNITE",$val).", \n";
		}
	echo "<BR>\n"; 
	}
?>
<form name="theform" action="list_pext.php" method="POST">

<table width="300" border="0">
<tr><td align="center" colspan="2">
<a name="haut"></a>
<span class="TRM"><?=$ss_prmev[title]?></span>
<? EchoTitIm1("CRITERES DE RECHERCHE"); ?>
<br>
  <?if ($ss_prmev[ro]=="M" && 
  	($ss_InfoUser[COPROFIL]=="SADMIN" || $ss_InfoUser[COPROFIL]=="DRH_ADM" || $ss_InfoUser[COPROFIL]=="EXT_GEST")) {
	 // bouton ajouter en modif, ET
  	// profil ADM DRH ou SUPERADM OU
	// ADM UF et externe
  
  JSpopup(); 
  ?>
  <a href="#" onclick="javascript:popup('popup_fichpext.php?TE=A');" title="Ajouter une personne"><img src="../images/ajout_pers.gif" border="0"></a>&nbsp;&nbsp;&nbsp;
  	  <? if ($ss_prmev[typers]=="E") { ?>
	  <a href="#" onclick="javascript:popup('popup_fichste.php?TE=A');" title="Ajouter une structure"><img src="../images/ajout_struct.gif" border="0"></a>
  	<?}
	}
  ?>
<br>
</td></tr>
<input type="hidden" name="tf_PEX_LMTYPERS" value="INPLIKE">
<input type="hidden" name="rq_PEX_LMTYPERS" value="EXT">
<input type="hidden" name="lc_FirstPers" value="0">

<?
$FCobj=new PYAobj();
$FCobj->NmBase=$DBDRHName;
$FCobj->NmTable="PERS_EXT";

$FCobj->NmChamp="PEX_LLNOMPERS";
DispLigReq();

$FCobj->NmChamp="PEX_LLPRENOMPERS";
DispLigReq();


echo "<tr><td class=\"backredc\"><b>Critères sur la structure d'appartenance</b></span><br></td><td>&nbsp;</td></tr>\n";
$FCobj->NmTable="SOCIETE";
$FCobj->NmChamp="SOC_NUTYACTIV";
DispLigReq();

$FCobj->NmTable="SOCIETE";
$FCobj->NmChamp="SOC_COPOSTAL";
DispLigReq();

$FCobj->NmTable="SOCIETE";
$FCobj->NmChamp="SOC_LLSOCIE";
DispLigReq();

if($ss_InfoUser[COPROFIL]=="UF_ADM" && $ss_prmev[ro]=="M") {
	// en modif
	// l'adm d'UF est limité à la modif des structure gérées par les UF dépendants de lui
	// les autres voient tout
	?>
	<input type="hidden" name="tf_SOC_NUUNITE" value="LDMEG">
	<input type="hidden" name="sp_NUUNITEGS" value="<?=implode(":", TbUFdep($ss_InfoUser[NUUNITEG]))?>">
	<?
	} // fin si modif par adm d'UF
else {
	$FCobj->NmTable="SOCIETE";
	$FCobj->NmChamp="SOC_NUUNITE";
	DispLigReq();
	}

?>
<tr><td colspan="2" align="center">
	<br><br>
  <? if ($ss_prmev[aff_pop]=="Y") {  // Bouton fermer si affichage en popup, appelé depuis page accueil intranet ?> 
	<a href="#" onclick="javascript:self.close()"><img src="../../intranet/partage/IMAGES/bout_fermer.gif" border="0" alt="fermer cette fenêtre"></A>
  <? echo (nbsp(3));
  } ?>
  <input type="image" src="../images/valider.gif" border="0">
</td></tr>
</table>
</form>
</body>
</html>



