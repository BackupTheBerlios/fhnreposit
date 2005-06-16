<?
require("infos.php");	
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...

JSpopup(530,500,"popinfosSoc"); 
?>
  <script language="JavaScript">
  // boite de confirmation  de suppression d'un enregistrement
    function ConfSuppr(url) {
    if (confirm('Etes vous certain de vouloir supprimer cet enregistrement ?'))
        self.location.href=url;
    }
  </script>

<?

if ($lc_FirstSoc!="") {
  $FirstSoc=$lc_FirstSoc;
  }
else if ($FirstSoc=="") // on vient forcément d'une autre page
  {$FirstSoc=0;
  }  

session_register("FirstSoc");

$limitc=" LIMIT $FirstSoc, $NbLigPPP";

// on balaye les noms de champs de cette table
$condexists=false;

// bricole spéciale pour récupérer le tableau des UF gérées par l'adm d'UF
// ce tableau est implodé et passé en post par var de formulaire 
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

$select="SOCIETE.*";
$from="SOCIETE";
$where=($where_per=="" ? "" : "where ".$where_per);

$orderb="ORDER BY SOC_LLSOCIE";

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
  <span class="normalred11px">Affichage enregistrement<?=$s?> <B><?echo ($FirstSoc+1)." à ".min($nbrows,($FirstSoc+$NbLigPPP));  ?></B> sur <b><?=$nbrows?></b></span><br><br>

  <?if ($ss_prmev[ro]=="M")
	{
	 // bouton ajouter en modif
	?>  
  <a href="#" onclick="javascript:popup('popup_fichste.php?TE=A');" title="Ajouter une structure"><img src="../images/ajout_struct.gif" border="0" width="70" height="11"></a>
   <? }
   if ($nbrows>=$NbLigFHB) { // affiche flèche vers le bas 
   	?><a href="#bas"><img src="../../../intranet/partage/IMAGES/bas.gif" border="0" title="vers le bas et la barre de navigation"></a>
	<? } ?>
  <br><br></td></tr>
  <?
 
  $req=msq("SELECT $select FROM $from $where $orderb $limitc");
  if ($debug) echo ("requete: SELECT $select FROM $from $where $orderb $limitc<br>\n");
  
  $nolig=0;	
  while ($tbValChp=mysql_fetch_array($req)) {
    $nolig++;
	echo "<tr><td><b>".($nolig + $FirstSoc)." .</b></td>";
	echo "<td align=\"left\">";
	$bulle="Cliquez pour ".($ss_prmev[ro]=="M" ? "modifier" : "visualiser")." les infos détaillées sur cette structure";
   	echo "<a href=\"#\" onclick=\"javascript:popup('popup_fichste.php?COSOCIE=$tbValChp[SOC_NUSOCIE]');\" title=\"Cliquez pour visualiser les infos détaillées sur cette structure\"><span class=\"boldred11px\">";
	echo $tbValChp[SOC_LLSOCIE];
	echo "</span></a>";// affiche Valeur Champ ds Liste
	echo "<span class=\"legendes9px\"><br>";
	echo ($tbValChp[SOC_TELSOCIE]!="" ? "tel.: ".$tbValChp[SOC_TELSOCIE]."&nbsp; " :"");
	echo ($tbValChp[SOC_PORSOCIE]!="" ? " mobile: ".$tbValChp[SOC_PORSOCIE]."&nbsp; " :"");
	if ($tbValChp[SOC_MAILSOCIE]!="") echo "Mel:&nbsp;".DispCustHT($tbValChp[SOC_MAILSOCIE]);
	if ($tbValChp[SOC_LLSITE]!="") echo "<BR>Web:&nbsp;".DispCustHT($tbValChp[SOC_LLSITE]);
	echo "<hr width=\"50%\" size=\"1\"></td></tr>";	
	} // fin boucle sur les structures
  ?>
<tr><td colspan="2" align="center">
<a name="bas">
  <?  if ($nbrows>=$NbLigFHB) { // affiche flèche vers le haut, et autre bouton nouveau que si assez de lignes à afficher 
  ?> 
  <a href="#haut"><img src="../../../intranet/partage/IMAGES/haut.gif" border="0" title="Haut de la liste"></a>

  <?if ($ss_prmev[ro]=="M")
	 { // bouton ajouter en modif
	 ?>
	<a href="#" onclick="javascript:popup('popup_fichste.php?TE=A');" title="Ajouter une structure"><img src="../images/ajout_struct.gif" border="0"></a>
	  <?
	  } // fin si en modif
   } // fin si assez de lignes à afficher
  // gestion des boutons suivant et précédent
  if ($FirstSoc>0) {
    echo "<A HREF=\"list_soc.php?lc_FirstSoc=".max(0,$FirstSoc-$NbLigPPP)."\" title=\"afficher les $NbLigPPP enregistrements précédents\"><img src=\"../../intranet/partage/IMAGES/preced.gif\" border=\"0\"></A>&nbsp;&nbsp;&nbsp;";
    }
  if (($FirstSoc+$NbLigPPP)<$nbrows) {
    echo "&nbsp;&nbsp;&nbsp;<A HREF=\"list_soc.php?lc_FirstSoc=".($FirstSoc+$NbLigPPP)."\" title=\"afficher les $NbLigPPP enregistrements suivants\"><img src=\"../../intranet/partage/IMAGES/suivant.gif\" border=\"0\"></A>";
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

