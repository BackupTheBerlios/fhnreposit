<?
require("infos.php");	
InitPage(true,"Fiche personne"); // initialise en envoyant les balises de début <HTML> etc ...

$TypEdit=1;
$TypEdittxt="MISE A JOUR";

$where="where PER_NUPERS='$NUPERS'";

$from="PERSONNE";
$select="PERSONNE.*";

if ($debug) echo ("requete: SELECT $select FROM $from $where<br>\n");

$req=msq("SELECT $select FROM $from $where");
?>

<script language="Javascript">
function ConfReset() {
    if (confirm('Etes vous certain de vouloir remettre toutes les champs à leurs valeurs par défaut ou d\'origine ?')) document.theform.reset();
}
window.resizeTo(480,500);
</script>

<form action="amact_mail.php" method="post" name="theform" ENCTYPE="multipart/form-data"> 
<input type="hidden" name="PER_NUPERS" value="<?=$NUPERS?>">

<div align="center">
<a name="haut"></a>
<table width="450" border="0">
<tr><td align="center" colspan="2" width="450">
<span class="TRM"><?=$TypEdittxt?></span>
<? EchoTitIm1("INFOS MAIL PERSONNE");?>
</td></tr>
<?
  InitObjsReq($req,$TypEdit); // appelle fonction qui initialise autant d'objets PYA qu'il y a
  // de champs ds la requête, tenant compte automatiquement de leur table d'appartenance  	  
	echo "<tr><td colspan=\"2\" align=\"center\"><span class=\"chapitrered12px\">";
	echo $CIL[PER_LMTITREPER]->ValChp;
	echo " ";
	echo $CIL[PER_LLPRENOMPERS]->ValChp;
	echo " ";
	echo $CIL[PER_LLNOMPERS]->ValChp;
	echo "</td></tr>\n";
	echo "<tr><td width=\"220\" class=\"backredc\"><b>Coordonnées électroniques</b></td><td width=\"220\" >&nbsp;</td></tr>\n";
	EchoLig("PER_TOPMAIL");
	if ($CIL[PER_MAILPERS]->ValChp=="") $CIL[PER_MAILPERS]->ValChp=strtolower($CIL[PER_LLPRENOMPERS]->ValChp).".".strtolower($CIL[PER_LLNOMPERS]->ValChp)."@haras-nationaux.fr";
	EchoLig("PER_MAILPERS");
	echo "<tr><td width=\"220\" class=\"backredc\"><b>Identifiant et contrôle d'exportation</b></td><td width=\"220\" >&nbsp;</td></tr>\n";
	EchoLig("PER_LCIDPERS");
	EchoLig("PER_EXPEXT");
?>
<tr><td colspan="2" align="center">
<a name="bas"><br>
<a href="#" onclick="javascript:self.close()"><img src="../../intranet/partage/IMAGES/bout_fermer.gif" border="0" alt="<?=($ss_prmev[ro]!="C" ? "Annuler tous les changement et ":"")?>fermer cette fenêtre"></A>
<? // boutons valider et annuler que quand read only false
    if ($ss_prmev[ro]!="C") { ?>
        &nbsp;&nbsp;&nbsp;&nbsp;<A HREF="javascript:ConfReset()" title="RAZ du formulaire"><IMG src="../../intranet/partage/IMAGES/annuler.gif" border="0"></a>
        &nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE="image" src="../../intranet/partage/IMAGES/valider.gif" border="0" onmouseover="self.status='Valider';return true">
    <?} ?>
</td></tr></table>
</div>
</BODY>
</HTML>

