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

// Typedit RP, Réduit Profil adm UF 
if ($ss_InfoUser[COPROFIL]!="DRH_ADM" && $ss_InfoUser[COPROFIL]!="SADMIN")
  {$TypEditRP="C";}
  else $TypEditRP=$TypEdit;

// verrue temporaire éventuelle pour mise à jour de certaines infos par les adm. d'UF
// ne reste plus que le correspondant 
  $TypEditTp=$TypEdit;
  
$where=($TypEdit!="" ? "where PER_NUPERS='$NUPERS'" :"where 1 LIMIT 1");

$from="PERSONNE LEFT JOIN INFOS_DRH ON PER_NUPERS=DRH_NUPERSO";
$select="PERSONNE.*,INFOS_DRH.*";

if ($debug) echo ("requete: SELECT $select FROM $from $where<br>\n");

$req=msq("SELECT $select FROM $from $where");


//JSstr_replace(); // colle fonction JS qui permet de remplacer des caractères dans une chaine
?>
<script language="Javascript">
function ConfReset() {
         if (confirm('Etes vous certain de vouloir remettre toutes les champs à leurs valeurs par défaut ou d\'origine ?')) document.theform.reset();
}

// Javascript qui met à jour l'Id, le mail et le N° SDL7 en fonction du nom premon
// appelé qd nouvel enregistrement sur  chgt de prenom ou de nom

<? if ($TypEdit=="") {echo "var NEWP=true;";} else echo "var NEWP=false;";
echo "\n";
?>
  
function MAJIdMailSDL7() {
  if (NEWP) {
     document.theform.PER_LCIDPERS.value =  document.theform.PER_LLPRENOMPERS.value.toLowerCase().substring(0,1) +  str_replace(' ','',document.theform.PER_LLNOMPERS.value.toLowerCase()).substring(0,14);
    }
  }  
<? // appel d'un javascript redimensionnant la fenêtre en largeur 
 if (!isset($AppIncl)) { ?>
   window.resizeTo(500,500);
<? } ?>
</script>

<form action="amact_pers.php" method="post" name="theform" ENCTYPE="multipart/form-data"> 
<input type="hidden" name="modif" value="<?=$TypEdit?>">
<input type="hidden" name="PER_NUPERS" value="<?=$NUPERS?>">
<input type="hidden" name="PER_LMTYPERS" value="INT">

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
  EchoTitIm1("FICHE PERSONNE");
  } // fin si pas appelé par liste?>

</td></tr>
<?
  InitObjsReq($req,$TypEdit); // appelle fonction qui initialise autant d'objets PYA qu'il y a
  // de champs ds la requête, tenant compte automatiquement de leur table d'appartenance      
  echo "<tr><td colspan=\"2\" align=\"center\"><span class=\"chapitrered12px\">";
  
  if (isset($AppIncl)) $CIL[PER_LMTITREPER]->Fccr="yes";
  $CIL[PER_LMTITREPER]->TypEdit=$TypEditRP; // titre était accessible temporairement
  $CIL[PER_LLPRENOMPERS]->TypEdit=$TypEditRP;
  $CIL[PER_LLNOMPERS]->TypEdit=$TypEditRP;
  
  
  echo ($CIL[PER_LMTITREPER]->TypEdit!="C" ? "Titre: " :"");
  $CIL[PER_LMTITREPER]->EchoEditAll();
  $CIL[PER_LLPRENOMPERS]->Tt_PdtMaj="onChange:MAJIdMailSDL7();";
  echo ($CIL[PER_LLPRENOMPERS]->TypEdit!="C" ? " Prénom: " :" ");
  $CIL[PER_LLPRENOMPERS]->EchoEditAll();
  echo ($CIL[PER_LLNOMPERS]->TypEdit!="C" ? " <BR/>Nom: " :" ");
  $CIL[PER_LLNOMPERS]->Tt_PdtMaj="onChange:MAJIdMailSDL7();";
  $CIL[PER_LLNOMPERS]->EchoEditAll();
  echo ($CIL[PER_LCSEXE]->TypEdit!="C" ? " Sexe: " :" ");
  $CIL[PER_LCSEXE]->EchoEditAll(); 

  echo "</span>";
  if ($ss_InfoUser[COPROFIL]=="SADMIN") echo "<span class=\"legendes9px\">(".$CIL[PER_NUPERS]->ValChp.")</span>";
  echo "<br>\n";
  echo ($TypEdit!="C" ? "Photo : " :""); 
  $CIL[PER_PHOTO]->EchoEditAll();
  echo "</td></tr>\n";
  
  echo "<tr><td width=\"220\" class=\"backredc\"><b>Coordonnées</b></td><td align=\"center\" width=\"220\" >".($CIL[PER_LCIDLDAP]->ValChp!="" ? "<a href=\"/webcalendar/week.php?user=".$CIL[PER_LCIDLDAP]->ValChp."\" target=\"_blank\"><img src=\"../images/webcal_icon.png\" width=\"16\" border=\"0\" title=\"consulter son agenda\"> Son agenda</a>&nbsp;" :"&nbsp;")."</td></tr>\n";
 
  EchoLig("PER_TELFIXE");
  EchoLig("PER_LCABREGE");
  EchoLig("PER_FAX");
  EchoLig("PER_PORPERS");
  
  $CIL[PER_LCIDLDAP]->TypeAff="HID";
  $CIL[PER_LCIDLDAP]->EchoEditAll();
  if ($TypEdit=="C") { // en consult, n'affiche le mail que s'il est actif et non vide
    if ($CIL[PER_TOPMAIL]->ValChp=="O" && $CIL[PER_MAILPERS]->ValChp!="") EchoLig("PER_MAILPERS");
    }
  else 
    { // en modif, affiche le mail et son activation éditable seulement pour les externes
    // seule l'admin de mail peut le changer pour les internes
    // ceci est réalisé sur une autre page
    // $FTE=($ss_prmev[typers]=="E" ? $TypEdit : "C");
    $FTE="C"; // pour les externes, c'est maintenant fait ailleurs
    EchoLig("PER_TOPMAIL",$FTE);
    EchoLig("PER_MAILPERS",$FTE);
    EchoLig("PER_EXPEXT",$FTE);
    EchoLig("PER_LCIDPERS",$FTE);
    if ($FTE=="C") { // en modif interne
      echo "<tr><td colspan=\"2\" class=\"legendered9px\">";
      echo "<U>N.B.:</U> Seul le(s) administrateur(s) de mail <b>";
      $rqrm=msq("select PER_LLPRENOMPERS,PER_LLNOMPERS,PER_MAILPERS from PERSONNE LEFT JOIN ENV_POSSEDE ON PER_NUPERS=POS_NUPERS WHERE POS_COPROFIL='ML_ADM' OR POS_COPROFIL='SADMIN'");
      while ($rwrm=mysql_fetch_array($rqrm)) {
        echo "<a href=\"mailto:".$rwrm[PER_MAILPERS]."?subject=Activation/modification de mail\">".$rwrm[PER_LLPRENOMPERS]." ".$rwrm[PER_LLNOMPERS]."</A>, ";
        }
      echo "</b>sont habilités à gérer les adresses mails internes ainsi que l'identifiant et l'exportation des coordonnées. Contactez-les pour ce faire.</td></tr>";
      }
     }
  
  //liste rouge qu'en édition
  if ($TypEdit!="C") EchoLig("PER_REDLIST");
  
  // affichage unité de rattachement et unité géographique
  if ($TypEditRP!="C") { // LD en edition
    EchoLig("DRH_NUUNITE",$TypEditRP);
    EchoLig("DRH_NUGEOPOS",$TypEditRP);
    EchoLig("DRH_NURESADM",$TypEditRP);
    }
  else { // normal avec popup en consult
    echo "<tr><td>Unité fonctionnelle de rattachement</td><td>";
    echo "<a class=\"boldred11px\" href=\"popup_fichUF.php?NUUNITE=".$CIL[DRH_NUUNITE]->ValChp."\">";
    $CIL[DRH_NUUNITE]->TypEdit="C";
    $CIL[DRH_NUUNITE]->EchoEditAll();
    echo "</a></td></tr>";
    } // fin consultation UF
  
  // fonction,spacialité,etc: sur 3 champs
  // en consultation, on les concatène  
  // on n'affiche que si <> de autre ou valeur non vide
   
  $CIL[DRH_COSPECIAL]->Fccr="yes"; // force les cases à cocher avec retour ligne entre chaque valeur
  $CIL[DRH_COCORRESP]->Fccr="yes";

  if ($TypEditRP=="C" ) { // était editable temporairement par les adm UF
    // en consult, n'affiche que si valide (qqchose à afficher)
    if ($CIL[DRH_COFONC]->ValChp!="AUT" || $CIL[DRH_COSPECIAL]->ValChp!=""|| $CIL[DRH_LLFONCCOMP]->ValChp!="") {
      echo "<tr><td>Fonction ou spécialité</td><td>";
      $CIL[DRH_COFONC]->TypEdit="C";
      $CIL[DRH_COSPECIAL]->TypEdit="C";
      $CIL[DRH_LLFONCCOMP]->TypEdit="C";
      
      $vpa=false; // pour gestion des tirets (valeur precedente affichee)
      if ($CIL[DRH_COFONC]->ValChp!="AUT") {
        $vpa=true;
        $CIL[DRH_COFONC]->EchoEditAll();}
      if ($CIL[DRH_COSPECIAL]->ValChp!="" && $CIL[DRH_COSPECIAL]->ValChp!=",")
        {
        if ($vpa) echo " - ";
        $CIL[DRH_COSPECIAL]->EchoEditAll();
        $vpa=true;
        }
      if ($CIL[DRH_LLFONCCOMP]->ValChp!="") {
        if ($vpa) echo " - "; 
        $CIL[DRH_LLFONCCOMP]->EchoEditAll();}
      echo "</td></tr>";
      } // fin si valide a afficher
    } // fin si consultation
  else { 
    EchoLig("DRH_COFONC",$TypEditRP);
    EchoLig("DRH_COSPECIAL",$TypEditRP);
    EchoLig("DRH_LLFONCCOMP",$TypEditRP);
    }

  // affiche la ligne correspondant si non vide, ou si mode <> edition
  if (($CIL[DRH_COCORRESP]->ValChp!="" && $CIL[DRH_COCORRESP]->ValChp!=",")||$TypEditTp!="C")
    EchoLig("DRH_COCORRESP",$TypEditTp);
    
    EchoLig("DRH_NUDOMCPT",$TypEditRP);
  
  // droit de voir les infos DRH: mode Personne et Profil OK
  if (DispInfDRH($ss_InfoUser[COPROFIL]) && $ss_prmev[typers]=="P") { 
    echo "<tr><td class=\"backredc\"><b>Informations DRH</b></td><td>&nbsp;</td></tr>\n";
    EchoLig("DRH_NUSDL7",$TypEditRP);
    //!! les TypEditTp servent pour la mise à jour tempo par les dm d'UF
    EchoLig("DRH_DATNAISS",$TypEditRP);
    EchoLig("DRH_PCTTRAV",$TypEditRP);
    EchoLig("DRH_NBHTRAV",$TypEditRP);
    if (isset($AppIncl)) $CIL[DRH_TYPARTT]->Fccr="yes";
    EchoLig("DRH_TYPARTT",$TypEditRP);
    EchoLig("DRH_DTDEBCONT",$TypEditRP);
    EchoLig("DRH_DTFINCONT",$TypEditRP);
    EchoLig("DRH_LCCATEG",$TypEditRP);
    EchoLig("DRH_LLACTIVITE",$TypEditRP);
    EchoLig("DRH_COSTATUT",$TypEditRP);
    EchoLig("DRH_COCORPS",$TypEditRP);
    EchoLig("DRH_COGRADE",$TypEditRP);
    EchoLig("DRH_DTMUTAT",$TypEditRP);
    // affiche le support budgetaire uniquement aux SADMIN,
    if ($ss_InfoUser[COPROFIL]=="SADMIN" || $ss_InfoUser[COPROFIL]=="DRH_ADM" || $ss_InfoUser[COPROFIL]=="DRH_LS") {
      EchoLig("DRH_NUSUPBUD",$TypEdit);}
    else { // les autres ne voient rien mais le champ passe en hidden (bug du 27/02/03)
      $CIL[DRH_NUSUPBUD]->TypeAff="HID";
           $CIL[DRH_NUSUPBUD]->EchoEditAll();
      }
    
      
    EchoLig("DRH_NUETUDE",$TypEditRP);
    EchoLig("DRH_LLCOMMENT",$TypEditRP);
    EchoLig("DRH_LLPROVE",$TypEditRP);
    } // fin si a le droit de voir/modifier les infos DRH
  // affiche les infos sur le user maj, date MAJ précédent en modif/creation uniquement
  // et pas si liste des fiches detaillées
  if ($TypEdit!="C" && !isset($AppIncl)) {
    EchoLig("PER_DTCREA",$TypEdit);
    EchoLig("PER_DTMAJ",$TypEdit);
    EchoLig("PER_COOPE",$TypEdit);
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

