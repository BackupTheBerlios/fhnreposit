<?
require("infos.php");	
InitPage(true,"critères de recherche annuaire"); // initialise en envoyant les balises de début <HTML> etc ...

if (isset($prech)) { // si param de recherche définis
	// valeurs par défaut 
	$ss_prmev[ro]="C"; // consultation par défaut
	$ss_prmev[typers]="I"; // trois possibilités: I (interne sans infos DRH), E (externe), P (Personnel IE avec Infos DRH)
		
	switch ($prech) {
		
		case "MP":
			$ss_prmev[title]="Mise à jour d'une personne interne";
			$ss_prmev[ro]="M";
			$ss_prmev[typers]="P";
			break;

		case "ME":
			$ss_prmev[title]="Plus géré sur cette page";
			$ss_prmev[ro]="";
			$ss_prmev[typers]="";
			break;
		
		case "MMI":
			$ss_prmev[title]="Mise à jour des adresses Mail internes";
			$ss_prmev[ro]="M";
			$ss_prmev[typers]="M"; // mails
			break;

		case "CE" :
			$ss_prmev[title]="Plus géré sur cette page";
			$ss_prmev[typers]="E";
			break;
	
		case "CP" :
			$ss_prmev[title]="Recherche pour consultation des infos DRH";
			$ss_prmev[typers]="P";
			break;
			
		case "CI":
		default:
			$ss_prmev[title]="Recherche d'une personne interne";
		break;
	}
	
	session_register("ss_prmev");
} // fin si nvaux param de recherche définis

$TYPERS= "INT";

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
<form name="theform" action="list_pers.php" method="POST">

<table width="300" border="0">
<tr><td align="center" colspan="2">
<a name="haut"></a>
<span class="TRM"><?=$ss_prmev[title]?></span>
<? EchoTitIm1("CRITERES DE RECHERCHE"); ?>
<br>
  <?if ($ss_prmev[ro]=="M" && 
  	($ss_InfoUser[COPROFIL]=="SADMIN" || $ss_InfoUser[COPROFIL]=="DRH_ADM")) {
	 // bouton ajouter en modif, ET
  	// profil ADM DRH ou SUPERADM OU
	
  
  JSpopup(); 
  ?>
  <a href="#" onclick="javascript:popup('popup_fichpers.php?TE=A');" title="Ajouter une personne"><img src="../../intranet/partage/IMAGES/ajouter.gif" border="0"></a>&nbsp;&nbsp;&nbsp;
	<? } // Fin si Modif
  ?>
<br>
</td></tr>
<input type="hidden" name="tf_PER_LMTYPERS" value="INPLIKE">
<input type="hidden" name="rq_PER_LMTYPERS" value="<?=$TYPERS?>">
<input type="hidden" name="lc_FirstPers" value="0">

<?
$FCobj=new PYAobj();
$FCobj->NmBase=$DBDRHName;
$FCobj->NmTable="PERSONNE";

$FCobj->NmChamp="PER_LLNOMPERS";
DispLigReq();

$FCobj->NmChamp="PER_LLPRENOMPERS";
DispLigReq();

if ($ss_prmev[typers]=="I" ) {
	// c'est ce qui est affiché par le recherche avancée accesible depuis la page d'accueil intranet
	$FCobj->NmChamp="DRH_NUUNITE";
	$FCobj->NmTable="INFOS_DRH";
	DispLigReq();
	
	$FCobj->NmChamp="DRH_NUGEOPOS";
	DispLigReq();

	$FCobj->NmChamp="DRH_COCORRESP";
	DispLigReq();

	$FCobj->NmChamp="DRH_COFONC";
	DispLigReq();

	$FCobj->NmChamp="DRH_COSPECIAL";
	DispLigReq();
	
	// en consultation interne on ne consulte que les actifs
	//CA DECONNE (AIT ?)
	?>
<!-- 	<input type="hidden" name="tf_TAC_AFFANN" value="INPLIKE">
	<input type="hidden" name="rq_TAC_AFFANN" value="%">
 -->
	<?
	// fonction renvoyant une chaine contenant les codes positions affichables dans l'annuaire séparés par des :
	$tbica=Ctbica();
	// passage multiples valeurs d'affichage dans l'annuaire
	?>
 	<input type="hidden" name="tf_DRH_LLACTIVITE" value="LDMEG">
	<input type="hidden" name="sp_DRH_LLACTIVITE" value="<?=$tbica?>">
	<?
	}// en consultation/MAJ d'infos DRH, on peut afficher différents filtres
elseif ($ss_prmev[typers]=="P" || $ss_prmev[typers]=="M") // même critères pour infos-drh et mails
	{
	$FCobj->NmTable="PERSONNE";
	$FCobj->NmChamp="PER_LCSEXE";
	DispLigReq();
	
	$FCobj->NmTable="INFOS_DRH";
	$FCobj->NmChamp="DRH_COFONC";
	DispLigReq();

	$FCobj->NmChamp="DRH_COSPECIAL";
	DispLigReq();

	$FCobj->NmChamp="DRH_LLFONCCOMP";
	DispLigReq();
	
	$FCobj->NmChamp="DRH_NUDOMCPT";
	DispLigReq();
	
	$FCobj->NmChamp="DRH_COCORRESP";
	DispLigReq();

	if($ss_InfoUser[COPROFIL]=="UF_ADM" || $ss_InfoUser[COPROFIL]=="UF_LS") {
		// seul l'adm d'UF est limité à la consult de ses ouailles
		// les autres voient tout
		?>
		<input type="hidden" name="tf_DRH_NUUNITE" value="LDMEG">
		<input type="hidden" name="sp_NUUNITEG" value="<?=implode(":", TbUFdep($ss_InfoUser[NUUNITEG]))?>">
		<?
		}
	else { // si pas adm UF (forcement consult DRH ou super admin)
		$FCobj->NmChamp="DRH_NUUNITE";
		$FCobj->NmTable="INFOS_DRH";
		DispLigReq(); 
	} // fin si adm UF
	
		
	$FCobj->NmTable="INFOS_DRH";
	$FCobj->NmChamp="DRH_NUGEOPOS";
	DispLigReq();

	$FCobj->NmChamp="DRH_NURESADM";
	DispLigReq();
	
	$FCobj->NmChamp="DRH_COSTATUT";
	DispLigReq();

	$FCobj->NmChamp="DRH_COCORPS";
	DispLigReq();

	$FCobj->NmChamp="DRH_COGRADE";
	DispLigReq();

	$FCobj->NmChamp="DRH_LLACTIVITE";
	DispLigReq();
    // verrue spéciale en javascript permettant de présélectionner les
	// valeurs de la LD correspondant aux appartenant à l'EPA
	?>
	<script language="javascript">
    var nbelemf = document.theform.elements.length;
	var tbciepa = '<?=Ctbicepa()?>';
    for (var i = 0; i < nbelemf; i++) {
		if (document.theform.elements[i].name.indexOf('rq_DRH_LLACTIVITE')>=0) {
			var maliste=document.theform.elements[i];

		// on est sur la bonne liste multiple
			for (var j=0; j<maliste.options.length;j++) {
				if (tbciepa.indexOf(maliste.options[j].value + ':')>=0) {
					maliste.options[j].selected=true;}
				else maliste.options[j].selected=false;
				} // fin boucle sur les options
			} // fin si c'est la bonne liste
    } // fin boucle sur les objets du formulaire
	</script>
	<?
	} // fin si Mail ou infos-DRH
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



