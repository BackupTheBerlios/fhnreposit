<?
require("infos.php");	
InitPage(true,"liste de réponses annuaire"); // initialise en envoyant les balises de début <HTML> etc ...

JSpopup(530,500,"popinfosDRH"); 
?>
  <script language="JavaScript">
  // boite de confirmation  de suppression d'un enregistrement
    function ConfSuppr(url) {
    if (confirm('Etes vous certain de vouloir supprimer cet enregistrement ?'))
        self.location.href=url;
    }
  </script>

<?

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
if (isset($sp_DRH_LLACTIVITE)) $rq_DRH_LLACTIVITE=explode(":",$sp_DRH_LLACTIVITE);

if ($cfrf==true) {
	$TAB_VARS=($_GET ? $_GET : $HTTP_GET_VARS);}
else $TAB_VARS=($_POST ? $_POST : $HTTP_POST_VARS);

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

$select="PERSONNE.*,INFOS_DRH.*";
$from="PERSONNE,INFOS_DRH LEFT JOIN TYPE_ACTIVITE ON DRH_LLACTIVITE=TAC_COTACT";
$where="where PER_NUPERS=DRH_NUPERSO ".($where_per!="" ? "AND ".$where_per : "");

$orderb="ORDER BY PER_LLNOMPERS";

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

  <?if ($ss_prmev[ro]=="M" && 
  	($ss_InfoUser[COPROFIL]=="SADMIN" || $ss_InfoUser[COPROFIL]=="DRH_ADM" )) {
	 // bouton ajouter en modif, ET
  	// profil ADM DRH ou SUPERADM
	?>  
  <a href="#" onclick="javascript:popup('popup_fichpers.php?TE=A');" title="Ajouter une personne"><img src="../../../intranet/partage/IMAGES/ajouter.gif" border="0"></a>&nbsp;&nbsp;&nbsp;
  	<?
  }
   if ($nbrows>=$NbLigFHB) { // affiche flèche vers le bas 
   	?><a href="#bas"><img src="../../../intranet/partage/IMAGES/bas.gif" border="0" title="vers le bas et la barre de navigation"></a>
	<? } ?>
  <br><br></td></tr>
  <?
  CNPYAL("INFOS_DRH","DRH_COFONC"); // instancie nvel objet $CIL[NomChamp]
  CNPYAL("INFOS_DRH","DRH_COSPECIAL"); // instancie nvel objet $CIL[NomChamp]
  CNPYAL("INFOS_DRH","DRH_NUUNITE"); // instancie nvel objet $CIL[NomChamp]
  CNPYAL("INFOS_DRH","DRH_NUGEOPOS"); // instancie nvel objet $CIL[NomChamp]
 
  $req=msq("SELECT $select FROM $from $where $orderb $limitc");
  if ($debug) echo ("requete: SELECT $select FROM $from $where $orderb $limitc<br>\n");
  
  $nolig=0;	
  while ($tbValChp=mysql_fetch_array($req)) {
    $nolig++;
	// booleen affichage des infos liste rouge ou pas
	$booldlr=($ss_InfoUser[COPROFIL]!=$COPROANO || $tbValChp[PER_REDLIST]=="N");
	echo "<tr><td><b>".($nolig + $FirstPers)." .</b></td>";
	echo "<td align=\"left\">";
	// lien vers popup que si pas liste rouge
	$bulle="Cliquez pour ".($ss_prmev[ro]=="M" ? "modifier" : "visualiser")." les infos détaillées sur cette personne";
	$adpop=(($ss_prmev[ro]=="M" && $ss_prmev[typers]=="M") ? "popup_mailpers.php" : "popup_fichpers.php");
	
	echo ($booldlr ? "<a href=\"#\" onclick=\"javascript:popup('$adpop?NUPERS=$tbValChp[PER_NUPERS]');\" title=\"$bulle\">":"")."<span class=\"chapitrered12px\">$tbValChp[PER_LMTITREPER] $tbValChp[PER_LLPRENOMPERS] $tbValChp[PER_LLNOMPERS]</span>".($booldlr ? "</a>":"")."\n";
	if ($ss_InfoUser[COPROFIL]=="SADMIN") echo "<span class=\"legendes9px\"> (".$tbValChp[PER_NUPERS].")</span>";
	echo "<BR>\n";
	// UF de ratachement : maintenant hiérarchique
	if ($tbValChp[DRH_NUUNITE]!="") {
		$bulle="Cliquez pour visualiser les infos détaillées sur cette unité fonctionnelle";  
		echo "<a href=\"#\" onclick=\"javascript:popup('popup_fichUF.php?NUUNITE=$tbValChp[DRH_NUUNITE]');\" title=\"$bulle\"><span class=\"boldred11px\">";			
		$CIL[DRH_NUUNITE]->ValChp=$tbValChp[DRH_NUUNITE];
		$CIL[DRH_NUUNITE]->EchoVCL();
		echo "</span></a>";
	} // fin si UF <>"" 
	
	// entité de rattachement géographique
	if ($tbValChp[DRH_NUGEOPOS]!="") {
		$bulle="Cliquez pour visualiser les infos détaillées sur cette entité géographique";  
		echo "<br/><a href=\"#\" onclick=\"javascript:popup('popup_fichGEO.php?NUUNITE=$tbValChp[DRH_NUGEOPOS]');\" title=\"$bulle\"><span class=\"boldred11px\">";			
		$CIL[DRH_NUGEOPOS]->ValChp=$tbValChp[DRH_NUGEOPOS];
		$CIL[DRH_NUGEOPOS]->EchoVCL();
		echo "</span></a>";
	} // fin si UF <>"" 
		
	// saute ligne si au moins une focntion renseignée
	if ($tbValChp[DRH_COFONC]!="AUT" || $tbValChp[DRH_LLFONCCOMP]!="" || ($tbValChp[DRH_COSPECIAL]!="" && $tbValChp[DRH_COSPECIAL]!=",")) echo "<BR>";
	$CIL[DRH_COFONC]->ValChp=$tbValChp[DRH_COFONC];
	if ($tbValChp[DRH_COFONC]!="AUT") { $CIL[DRH_COFONC]->EchoVCL();
		// tiret après ce lib que si au moins un après non vide 
		if ($tbValChp[DRH_LLFONCCOMP]!="" || ($tbValChp[DRH_COSPECIAL]!="" && $tbValChp[DRH_COSPECIAL]!=",")) echo " - ";
			}
	if ($tbValChp[DRH_COSPECIAL]!="" && $tbValChp[DRH_COSPECIAL]!=",") 
		{ $CIL[DRH_COSPECIAL]->ValChp=$tbValChp[DRH_COSPECIAL];  
		$CIL[DRH_COSPECIAL]->EchoVCL();
		if ($tbValChp[DRH_LLFONCCOMP]!="") echo " - ";
		} 
	echo $tbValChp[DRH_LLFONCCOMP];
	echo "<span class=\"legendes9px\"><br>";
	if ($booldlr){
		// si affichage liste rouge ou personne n'y est pas
		echo ('<span class="spctel">'.($tbValChp[PER_TELFIXE]!="" ? "tel.: ".$tbValChp[PER_TELFIXE]."&nbsp; " :"").'</span>');
		echo ('<span class="spctel">'.($tbValChp[PER_PORPERS]!="" ? " mobile: ".$tbValChp[PER_PORPERS]."&nbsp; " :"").'</span>');
		echo ($tbValChp[PER_LCIDLDAP]!="" ? "<br/><a href=\"/webcalendar/week.php?user=".$tbValChp[PER_LCIDLDAP]."\" target=\"_blank\"><img src=\"../images/webcal_icon.png\" width=\"16\" border=\"0\" title=\"consulter son agenda\"></a>&nbsp;" :"");
		if ($tbValChp[PER_MAILPERS]!="" && $tbValChp[PER_TOPMAIL]=="O") echo "Mel:&nbsp;".DispCustHT($tbValChp[PER_MAILPERS]);
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

  <?if ($ss_prmev[ro]=="M" && 
  	($ss_InfoUser[COPROFIL]=="SADMIN" || $ss_InfoUser[COPROFIL]=="DRH_ADM" )) {
	 // bouton ajouter en modif, ET
  	// profil ADM DRH ou SUPERADM OU
	// ADM UF et externe
	  echo (nbsp(3));?>  
	  <a href="#" onclick="javascript:popup('popup_fichpers.php?TE=A');" title="Ajouter une personne"><img src="../../../intranet/partage/IMAGES/ajouter.gif" border="0"></a>
    <? echo (nbsp(3));
	  } // fin si en modif
   } // fin si assez de lignes à afficher
  // gestion des boutons suivant et précédent
  if ($FirstPers>0) {
    echo "<A HREF=\"list_pers.php?lc_FirstPers=".max(0,$FirstPers-$NbLigPPP)."\" title=\"afficher les $NbLigPPP enregistrements précédents\"><img src=\"../../intranet/partage/IMAGES/preced.gif\" border=\"0\"></A>&nbsp;&nbsp;&nbsp;";
    }
  if (($FirstPers+$NbLigPPP)<$nbrows) {
    echo "&nbsp;&nbsp;&nbsp;<A HREF=\"list_pers.php?lc_FirstPers=".($FirstPers+$NbLigPPP)."\" title=\"afficher les $NbLigPPP enregistrements suivants\"><img src=\"../../intranet/partage/IMAGES/suivant.gif\" border=\"0\"></A>";
    }
	?> </td></tr><?
	} // fin si nbrows >0
  ?>
<tr><td colspan="2" align="center">
<? if ($ss_prmev[aff_pop]=="Y") {  // Bouton fermer si affichage en popup, depuis page accueil intranet ?> 
	<a href="#" onclick="javascript:self.close()"><img src="../../intranet/partage/IMAGES/bout_fermer.gif" border="0" alt="fermer cette fenêtre"></A>
  <? echo (nbsp(3));} ?>
  <a href="req_rech_pers.php"><img src="../../../intranet/partage/IMAGES/retour.gif" border="0" title="retour à la grille de requête" onmouseover="self.status='Retour';return true" width="70" height="11"></a>
</td></tr></table>
</BODY>
</HTML>

