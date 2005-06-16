<?
require("infos.php");	
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...
JSpopup(530,500); 
?>
<style type="text/css">
	#bgr { }
	#modules { position: absolute; z-index: 3; top: 10px; left: 10px; visibility: visible }

	#fond { position: absolute; z-index: 1; top: 0px; left: 80px; visibility: visible }

	a:active  { color: white; text-decoration: none }
	a:link { color: white; text-decoration: none }
	a:visited { color: white; text-decoration: none }
	a:hover {color: #D699AD; text-decoration: none}

.grosliens {
	text-align:center;
	font-weight: bold;
	line-height: 13px; 
	font-size:11px;
	color: #FFFFFF;
	padding-bottom: 2px;
	padding-left: 5px;
	padding-right: 5px;
	padding-top: 2px}
	
.liensmoy {
	text-align:center;
	font-weight: bold;
	line-height: 13px; 
	font-size:10px;
	color: #FFFFFF;
	padding-bottom: 2px;
	padding-left: 5px;
	padding-right: 5px;
	padding-top: 2px}

</style>				
<body id="bgr">
<div id="fond">
	<table border="0" cellpadding="0" cellspacing="1" width="600">
		<tr>
			<td align="center" valign="middle" bgcolor="white"><? if ($debug) 	DispDebug();?>
<img src="../images/fond_accueil4_vm3.jpg"></td>
		</tr>
	</table>
</div>

<div id="modules" align="left">
		<form action="rcf_rech.php" name="theform" method="post">
<table width="780" border="0" align="left" cellpadding="0" cellspacing="0">
	<tr>
		<td width="230" align="center" valign="top"><? EchoTitIm1(nbsp(7)."Recherche Libre".nbsp(7),"_b");?>
		<table width="210" align="center" style="color: #990033">
		<?
 	$ss_prmev[ro]="C"; // consultation par défaut
	$ss_prmev[typers]="I"; // I (interne sans infos DRH)
	session_register("ss_prmev");

	$FCobj=new PYAobj();
	$FCobj->NmBase=$DBDRHName;
	$FCobj->NmTable="PERSONNE";
	
	$FCobj->NmChamp="PER_LLNOMPERS";
	DispLigReq();
	
	$FCobj->NmChamp="PER_LLPRENOMPERS";
	DispLigReq();

	$FCobj->NmChamp="DRH_NUUNITE";
	$FCobj->NmTable="INFOS_DRH";
	//DispLigReq();
	
	$FCobj->InitPO(); // initialise l'objet
	echo "<tr><td colspan=\"2\">\n";
	echo "<b>".$FCobj->Libelle."</b><br>\n";
	echo ($FCobj->Comment!="" ? "<br><span class=\"legendes9px\">".$FCobj->Comment."</span>" : "");
	$FCobj->EchoFilt(false); // affiche filtre sans négation 
	echo "</td></tr>\n";
	 ?>
	<tr><td>&nbsp;</td><td>
	<input type="hidden" name="tf_PER_LMTYPERS" value="INPLIKE">
	<input type="hidden" name="rq_PER_LMTYPERS" value="INT">
	<input type="hidden" name="lc_FirstPers" value="0">
	<?
	// fonction renvoyant une chaine contenant les codes positions affichables dans l'annuaire séparés par des :
	$tbica=Ctbica();
	// passage multiples valeurs d'affichage dans l'annuaire
	?>
 	<input type="hidden" name="tf_DRH_LLACTIVITE" value="LDMEG">
	<input type="hidden" name="sp_DRH_LLACTIVITE" value="<?=$tbica?>">
	<input type="image" src="../images/valider.gif" border="0">
	</td></tr>
	<tr><td>&nbsp;</td><td align="right">
	<br><a href="frame02.php?nrp0=4&cont=req_rech_pers.php?prech=CI" title="recherche avancée"><img src="../images/recherche_av.gif" border="0"></a></td></tr>
		</table>
	</form>
		<? EchoTitIm1(nbsp(10)."Accès Directs".nbsp(10),"_b"); ?>
		
			<table border="0" cellpadding="5" cellspacing="2">
				<tr align="center" valign="middle">
				<td>
				<!-- <td class="grosliens" bgcolor="#A491BE"> Ancien fond-->
				<a href="http://HARAS:HARAS@195.115.122.8:81" target="_blank"><img src="../images/logo_nocia2.gif" border="0" width="63" height="50" alt="Nocia, le portail Intranet du Ministère"></a></td>
<!-- 				<td class="grosliens" bgcolor="#600F63"> ANcien fond -->	
			<td>			
			<a href="http://www.legifrance.gouv.fr/citoyen/new_officiels.ow" target="_blank"><img src="../images/logo_legifrance.gif" border="0" width="130" height="43" alt="Légifrance.gouv.fr, le service public de la diffusion du droit"></a>
				</td></tr>
			</table>
				
		</td>
		<td width="250" align="center" valign="top">
		&nbsp;</td>
<!-- 		<img src="../images/imag_mil_acc_prov2.jpg" border="0"></td>
 -->		
 	   <td width="275" align="center" valign="top" align="center">
		<? EchoTitIm1(nbsp(20)."Actualités".nbsp(20),"_b"); ?>
		<table border="0" cellpadding="0" cellspacing="5" align="center"><tr><td>
			<IFRAME SRC="actus_pa.php" frameborder="no" MARGINWIDTH="1" MARGINHEIGHT="1" 			SCROLLING="auto" width="265" height="290" align="center"></IFRAME>
			</td></tr>
		</table>

<table width="90%" border="0" cellpadding="0" cellspacing="1" align="center">
			<tr align="center"><td class="liensmoy" bgcolor="#AD9AC3">
			<a href="#" onclick="popup('../../intranet/HTML/actu_vrac.php?Emplact=DRH_notserv',500,350);">
			Notes de<br>service</a></td>
			<td bgcolor="#6C4D96" class="liensmoy">
		<a href="#" onclick="popup('../../intranet/HTML/actu_vrac.php?Emplact=DRH_txtregl',500,300);">
Textes<br>réglementaires</a></td>
			<td bgcolor="#600F63" class="liensmoy">
		<a href="#" onclick="popup('../../intranet/HTML/actu_vrac.php?Emplact=DRH_mobint',500,300);">
Mobilité</a></td>
			<td bgcolor="#991F5D" class="liensmoy">
		<a href="#" onclick="popup('travaux.php?DispRet=true',500,300);">
Imprimés</a></td></tr>
		</table></td>
	</tr>
</table>
</div>
</body>
</html>
