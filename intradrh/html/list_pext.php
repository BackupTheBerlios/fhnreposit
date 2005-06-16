<?
require("infos.php");	
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...

JSpopup(530,500,"popinfospers"); 

if ($lc_FirstPers!="") {
  $FirstPers=$lc_FirstPers;
  }
else if ($FirstPers=="") // on vient forcément d'une autre page
  {$FirstPers=0;
  }  

session_register("FirstPers");

$limitc=" LIMIT $FirstPers, $NbLigPPP";

// on balaye les noms de champs de cette table
$condexists=false;

// bricole spéciale pour récupérer le tableau des UF gérées par l'adm d'UF
// ce tableau est implodé et passé en post par var de formulaire 
if (isset($sp_NUUNITEG)) $rq_DRH_NUUNITE=explode(":",$sp_NUUNITEG);
if (isset($sp_NUUNITEGS)) $rq_SOC_NUUNITE=explode(":",$sp_NUUNITEGS);
// idem pour les actifs (sinon la requete avec 3 joins déconne)

if ($cfrf==true) {
	$TAB_VARS=$HTTP_GET_VARS;}
else $TAB_VARS=$HTTP_POST_VARS;

// cherche les var qui sont des variables de filtre
foreach ($TAB_VARS as $NmVar=>$ValVar) {
	if (substr($NmVar, 0, 3)=="tf_") { // au moins une var de filtre existe
	// reconstitution nom de la var du Type Requête
	  $NomChp=substr($NmVar,3);
	  $nmvarTR="tf_".$NomChp; // type de filtre
	  $nmvarVR="rq_".$NomChp; // Valeur de la Requete
	  $nmvarNEG="neg_".$NomChp; // Negation
	  $cond=SetCond ($$nmvarTR,$$nmvarVR,$$nmvarNEG,$NomChp);          
	  if ($cond!="") {
		 $condexists=true;
		 if ($new_wh!="") $new_wh.=" AND ";
	     $new_wh.=$cond;
	     }
	} // sin si variable de filtre
} // fin boucle sur les var POSTEES

// ne réenregistre que si les variables ont été définies ou changées
if ($condexists) {
	$where_per=$new_wh; 
	session_register ("where_per");}

$select="PERS_EXT.*,SOCIETE.*";
$from="PERS_EXT LEFT JOIN SOCIETE ON PEX_COSOCIE=SOC_NUSOCIE";
$where=($where_per=="" ? "" : "where ".$where_per);

$orderb="ORDER BY PEX_LLNOMPERS";

$result=msq("SELECT 1 FROM $from $where $orderb");
// on compte le nombre de ligne renvoyées par la requête
$nbrows=mysql_num_rows($result);

if ($debug) {
echo "<H1>Nbre $nbrows</H1>";
	echovar("where");
	echovar("select");
	echovar("from");
	DispDebug();
	}
	?>
<table width="300" border="0">
<tr><td align="center" colspan="2">
<a name="haut"></a>
<span class="TRM"><?=$ss_prmev[title]?></span>
<? EchoTitIm1("RESULTAT"); ?>
<?
// On affiche le resultat
if ($nbrows==0)// Si nbrésultat = 0
    {
    ?>
    <br><br><span class="chapitrered12px">Aucun enregistrement accessible ! <br><br>Veuillez spécifier des critères de recherche moins restrictifs!</span><br><br></td></tr>
    <?
  }
else if ($nbrows>$NbRepMx)// Si nbrésultat = 0
    {
    ?>
    <br><span class="chapitrered12px">Il y a plus de <?=$NbRepMx?> réponses ! <br><br>Veuillez spécifier des critères de recherche plus restrictifs!</span><br><br></td></tr>
    <?
  }
else // si nbrésultat>0 
    {
    $s=($nbrows>1 ? "s" : "");
    ?>

  <br><!-- <span class="chapitrered12px"><?=$nbrows?> enregistrement<?=$s?> accessible<?=$s?> dans cette table </span><br> -->
  <span class="normalred11px">Affichage enregistrement<?=$s?> <B><?echo ($FirstPers+1)." à ".min($nbrows,($FirstPers+$NbLigPPP));  ?></B> sur <b><?=$nbrows?></b></span><br><br>

  <?if ($ss_prmev[ro]=="M")
	{
	 // bouton ajouter en modif
	?>  
  <a href="#" onclick="javascript:popup('popup_fichpext.php?TE=A');" title="Ajouter une personne"><img src="../images/ajout_pers.gif" border="0" width="70" height="11"></a>&nbsp;&nbsp;&nbsp;
  <a href="#" onclick="javascript:popup('popup_fichste.php?TE=A');" title="Ajouter une structure"><img src="../images/ajout_struct.gif" border="0" width="70" height="11"></a>
   <? }
   if ($nbrows>=$NbLigFHB) { // affiche flèche vers le bas 
   	?><a href="#bas"><img src="../../../intranet/partage/IMAGES/bas.gif" border="0" title="vers le bas et la barre de navigation"></a>
	<? } ?>
  <br><br></td></tr>
  <?
  CNPYAL("PERS_EXT","PEX_COSOCIE"); // instancie nvel objet $CIL[NomChamp]
  CNPYAL("PERS_EXT","PEX_FONCTION"); // instancie nvel objet $CIL[NomChamp]
 
  $req=msq("SELECT $select FROM $from $where $orderb $limitc");
  if ($debug) echo ("requete: SELECT $select FROM $from $where $orderb $limitc<br>\n");
  
  $nolig=0;	
  while ($tbValChp=mysql_fetch_array($req)) {
    $nolig++;
	// booleen affichage des infos liste rouge ou pas
	$booldlr=($ss_InfoUser[COPROFIL]!=$COPROANO || $tbValChp[PEX_REDLIST]=="N");
	echo "<tr><td><b>".($nolig + $FirstPers)." .</b>";
	if ($ss_prmev[ro]=="M") echo "<a href=\"#\" onclick=\"javascript:popup('del_pext.php?NUPERS=$tbValChp[PEX_NUPERS]');\" title=\"Supprimer cette personne\"><IMG SRC=\"../images/del.gif\" BORDER=\"0\"></a>";
	echo "</td>";
	echo "<td align=\"left\">";
	$bulle="Cliquez pour ".($ss_prmev[ro]=="M" ? "modifier" : "visualiser")." les infos détaillées sur cette personne";
	$adpop="popup_fichpext.php";
	echo ($booldlr ? "<a href=\"#\" onclick=\"javascript:popup('$adpop?NUPERS=$tbValChp[PEX_NUPERS]');\" title=\"$bulle\">":"")."<span class=\"chapitrered12px\">$tbValChp[PEX_LMTITREPER] $tbValChp[PEX_LLPRENOMPERS] $tbValChp[PEX_LLNOMPERS]</span>".($booldlr ? "</a>":"")."\n";
	if ($ss_InfoUser[COPROFIL]=="SADMIN") echo "<span class=\"legendes9px\"> (".$tbValChp[PEX_NUPERS].")</span>";
	echo "<BR>\n";
	// structure de ratachement 
	if ($tbValChp[PEX_COSOCIE]!="") {  
		$CIL[PEX_COSOCIE]->ValChp=$tbValChp[PEX_COSOCIE];
       	echo "<a href=\"#\" onclick=\"javascript:popup('popup_fichste.php?COSOCIE=$tbValChp[PEX_COSOCIE]');\" title=\"Cliquez pour visualiser les infos détaillées sur cette structure\"><span class=\"boldred11px\">";
		$CIL[PEX_COSOCIE]->EchoVCL();
		echo "</span></a>";// affiche Valeur Champ ds Liste
		} // fin si SOCIETE <>""
	echo " &#149; ";
	$CIL[PEX_FONCTION]->ValChp=$tbValChp[PEX_FONCTION];
	$CIL[PEX_FONCTION]->EchoVCL();
	echo "<span class=\"legendes9px\"><br>";
	if ($booldlr){
		// si affichage liste rouge ou personne n'y est pas
		echo ($tbValChp[PEX_TELFIXE]!="" ? "tel.: ".$tbValChp[PEX_TELFIXE]."&nbsp; " :"");
		echo ($tbValChp[PEX_PORPERS]!="" ? " mobile: ".$tbValChp[PEX_PORPERS]."&nbsp; " :"");
		if ($tbValChp[PEX_MAILPERS]!="" && $tbValChp[PEX_TOPMAIL]=="O") echo "Mel:&nbsp;".DispCustHT($tbValChp[PEX_MAILPERS]);
		} 
	else {
		echo "<span class=\"legendesred9px\">Cette personne ne souhaite pas divulguer ses coordonnées<br>Veuillez contacter son unité fonctionnelle de rattachement</span>";
		}
			
	echo "<hr width=\"50%\" size=\"1\"></td></tr>";	
	} // fin boucle sur ls personnes
  ?>
<tr><td colspan="2" align="center">
<a name="bas">
  <?  if ($nbrows>=$NbLigFHB) { // affiche flèche vers le haut, et autre bouton nouveau que si assez de lignes à afficher 
  ?> 
  <a href="#haut"><img src="../../../intranet/partage/IMAGES/haut.gif" border="0" title="Haut de la liste"></a>

  <?if ($ss_prmev[ro]=="M")
	 {
	 // bouton ajouter en modif
	  echo (nbsp(3));?>  
	  <a href="#" onclick="javascript:popup('popup_fichpext.php?TE=A');" title="Ajouter une personne"><img src="../images/ajout_pers.gif" border="0"></a>
      <? echo (nbsp(3));?>
		  <a href="#" onclick="javascript:popup('popup_fichste.php?TE=A');" title="Ajouter une structure"><img src="../images/ajout_struct.gif" border="0"></a>
	  <?
	  } // fin si en modif
   } // fin si assez de lignes à afficher
  // gestion des boutons suivant et précédent
  if ($FirstPers>0) {
    echo "<A HREF=\"list_pext.php?lc_FirstPers=".max(0,$FirstPers-$NbLigPPP)."\" title=\"afficher les $NbLigPPP enregistrements précédents\"><img src=\"../../intranet/partage/IMAGES/preced.gif\" border=\"0\"></A>&nbsp;&nbsp;&nbsp;";
    }
  if (($FirstPers+$NbLigPPP)<$nbrows) {
    echo "&nbsp;&nbsp;&nbsp;<A HREF=\"list_pext.php?lc_FirstPers=".($FirstPers+$NbLigPPP)."\" title=\"afficher les $NbLigPPP enregistrements suivants\"><img src=\"../../intranet/partage/IMAGES/suivant.gif\" border=\"0\"></A>";
    }
	?> </td></tr><?
	} // fin si nbrows >0
  ?>
<tr><td colspan="2" align="center">
<? if ($ss_prmev[aff_pop]=="Y") {  // Bouton fermer si affichage en popup, depuis page accueil intranet ?> 
	<a href="#" onclick="javascript:self.close()"><img src="../../intranet/partage/IMAGES/bout_fermer.gif" border="0" alt="fermer cette fenêtre"></A>
  <? echo (nbsp(3));} ?>
  <a href="req_rech_pext.php"><img src="../../../intranet/partage/IMAGES/retour.gif" border="0" title="retour à la grille de requête" onmouseover="self.status='Retour';return true" width="70" height="11"></a>
</td></tr></table>
</BODY>
</HTML>

