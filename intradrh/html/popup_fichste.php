<?
require("infos.php");	
InitPage(true,"Fiche soci�t�"); // initialise en envoyant les balises de d�but <HTML> etc ...
// devra faire la diff entre cr�ation et modif

// fait la diff entre cr�ation et modif

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
	// s�curit�: en fait si rien ds ss_prmev, repasse en consultation
	case "C":
	default:
		$TypEdit="C";
		break;
}  


?>
<div align="center">
<a name="haut"></a>
<?
if ($debug) {
	echo "Cet ADM de l'UF $ss_InfoUser[NUUNITEG] g�re les UF suivantes: ";
	$TbUf=TbUFdep($ss_InfoUser[NUUNITEG]);
	foreach ($TbUf as $val) {
		echo Recuplib("UNITE_FONCTION","UFO_NUUNITE","UFO_LLUNITE",$val).", \n";
		}
	echo "<BR>\n"; 
	}
?>



<script language="Javascript">
function ConfReset() {
	if (confirm('Etes vous certain de vouloir remettre toutes les champs � leurs valeurs par d�faut ou d\'origine ?')) document.theform.reset();
}

window.resizeTo(480,500);
</script>

<form action="amact_soc.php" method="post" name="theform" ENCTYPE="multipart/form-data"> 
<input type="hidden" name="modif" value="<?=$TypEdit?>">


<table width="450" border="0">
<tr><td align="center" colspan="2" width="450">
<span class="TRM"><?=$TypEdittxt?></span>
<? EchoTitIm1("FICHE SOCIETE");?>
</td></tr>
<?

  $req=msq("SELECT * FROM SOCIETE where SOC_NUSOCIE='$COSOCIE'");
  
  InitObjsReq($req,$TypEdit);
	echo "<tr><td colspan=\"2\" class=\"chapitrered12px\" align=\"center\">";
	echo "<input type=\"hidden\" name=\"SOC_NUSOCIE\" value=\"".$CIL[SOC_NUSOCIE]->ValChp."\">";
	echo ($TypEdit!="C" ? "Raison sociale: " : "");
	$CIL[SOC_LLSOCIE]->EchoEditAll(); // nom de la soci�t�
	echo "<br>";
	echo ($TypEdit!="C" ? "Logo ou fichier associ�: " : "");
	$CIL[SOC_FICHASS]->EchoEditAll(); // logo
	echo "</td></tr>\n";
	EchoLig("SOC_TELSOCIE");
	EchoLig("SOC_FAXSOCIE");
	EchoLig("SOC_PORSOCIE");
	EchoLig("SOC_LCABREGE");
	EchoLig("SOC_MAILSOCIE");
	EchoLig("SOC_LLSITE");
	echo "<tr><td>Coordonn�es postales</td><td>";
	echo ($TypEdit!="C" ? "Adresse :": "");
	$CIL[SOC_LLADRES]->EchoEditAll();
	echo "<BR>";
	echo ($TypEdit!="C" ? "Complement d'adresse :": "");
	if ($CIL[SOC_LLADRES2]->ValChp!="" || $TypEdit!="C") { 
		 $CIL[SOC_LLADRES2]->EchoEditAll();
		 echo "<BR>";
		 }
	echo "<BR>";
	echo ($TypEdit!="C" ? "Code P. :": "");
	$CIL[SOC_COPOSTAL]->EchoEditAll();
	echo ($TypEdit!="C" ? "Ville :": "");
	$CIL[SOC_LLCOMMU]->EchoEditAll()."</td></tr>\n";
	EchoLig("SOC_COPAYS");
	EchoLig("SOC_NUTYACTIV");
	EchoLig("SOC_COMMENT");
	EchoLig("SOC_PLANACC"); // plan d'acc�s
	// si adm d'UF et cr�ation ou modif
	// pour la liste d'UF, n'affiche que celle dont il est l'adm
	
	
	if ($TypEdit=="")  $CIL[SOC_NUUNITE]->ValChp=$ss_InfoUser[NUUNITEG];// en cr�ation, on met tjrs par def l'UF g�r�e par le user
//	if (($ss_InfoUser[COPROFIL]=="UF_ADM"  || $ss_InfoUser[COPROFIL]=="EXT_GEST") && $TypEdit!="C") {
	if (($ss_InfoUser[COPROFIL]=="UF_ADM" ) && $TypEdit!="C") {
		echo "<tr><td>".$CIL[SOC_NUUNITE]->Libelle."</td><td>";
		echo " Unit� $ss_InfoUser[NUUNITEG]";
		$TbUf=TbUFdep($ss_InfoUser[NUUNITEG]); // super fonction qui ram�ne un tableau associatif
		foreach ($TbUf as $val) {
			$tbld[$val]=Recuplib("UNITE_FONCTION","UFO_NUUNITE","UFO_LLUNITE",$val);
			// detection de la valeur existante
			if ($CIL[SOC_NUUNITE]->ValChp==$val) $tbld[$val]=$VSLD.$tbld[$val];
			} // fin boucle d�tection valeur
		DispLD($tbld,"SOC_NUUNITE","no");
		}
	else EchoLig("SOC_NUUNITE");

  ?>
<tr><td colspan="2" align="center">
<a name="bas">
<br>
<a href="javascript:self.close();"><img src="../../intranet/partage/IMAGES/bout_fermer.gif" border="0" width="70" height="11" alt="Fermer cette fen�tre"></a>
<? // boutons valider et annuler que quand read only false
    if ($ss_prmev[ro]!="C") { ?>
        &nbsp;&nbsp;&nbsp;&nbsp;<A HREF="javascript:ConfReset()" title="RAZ du formulaire"><IMG src="../../intranet/partage/IMAGES/annuler.gif" border="0"></a>
        &nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE="image" src="../../intranet/partage/IMAGES/valider.gif" border="0">
    <?} ?>
</td></tr></table>
</div></BODY>
</HTML>

