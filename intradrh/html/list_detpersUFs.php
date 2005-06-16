<?
require("infos.php");	
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...

JSpopup(530,500); 
?>	
<div align="center"><a name="haut"></a>
<span class="TRM">Fiches personnes détaillées</span>
<? EchoTitIm1("RESULTAT"); 
// cherche les UF dep 
$TbUf=TbUFdep($ss_InfoUser[NUUNITEG]);

$nolig=1;
$ss_prmev[ro]="M";
foreach ($TbUf as $val) {
	$LLUNITE= Recuplib("UNITE_FONCTION","UFO_NUUNITE","UFO_LLUNITE",$val);
	$rqpd=msq("select PER_NUPERS from PERSONNE left join INFOS_DRH on PER_NUPERS=DRH_NUPERSO where DRH_NUUNITE='$val'");
	while ($rwpd=mysql_fetch_row($rqpd)) {
		echo "<span class=\"TRM\">$LLUNITE</span>";
		$NUPERS=$rwpd[0];
		$AppIncl=true; // appel par include
		include("./popup_fichpers.php");
		echo "<br>Page ".$nolig++." <BR>";
		echo "<HR>";
		echo "<DIV STYLE=\"page-break-before:always\"></DIV>"; // saut de page
		} // boucle sur les pers de l'UF	
	} // boucle sur les UF géré par la pers courante
?>
</div></BODY>
</HTML>

