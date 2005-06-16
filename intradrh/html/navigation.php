<?
	require("infos.php");	
	InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...
?>

	<table width="71%" border="0" align="center">
	    <tr> 
	      <td width="32" valign="middle"><img src="../images/tiret_droite.gif" width="32" height="5"></td>
	      <td><h1>Bienvenue sur l'INTRANET DRH !</h1></td>
	    </tr>
	    <tr> 
	      <td width="32" height="56">&nbsp;</td>
	      <td><? if ($ss_InfoUser[NUPERS]!="AN") 
echo "Bonjour $ss_InfoUser[TITRE] $ss_InfoUser[PRENOM] $ss_InfoUser[NOM] !<BR><BR>"; ?> 
Vous êtes actuellement connecté avec le profil <?=$ss_InfoUser[LLPROFIL]?><br><br>
<u><b>Note aux administrateurs d'UF:</b> </u> vous disposez maintenant dans le menu Espace Unité -> Personnel HN d'une nouvelle fonctionnalité vous permettant d'<b>éditer en une seule fois l'ensembles des fiches détaillées</b> des personnes dont vous avez la responsabilité 
</td>
	    </tr>
	    <tr> 
	      <td width="32" height="56"><img src="../images/tiret_droite.gif" width="32" height="5"></td>
	      <td>Pour consulter l'annuaire interne, <b><a href="frame02.php?nrp0=4&cont=req_rech_pers.php?prech=CI">cliquez ICI</A></b></td>
	    </tr>
	    <tr> 
	      <td width="32" height="56">&nbsp;</td>
	      <td>Pour changer de profil, cliquez <a href="identification.php">ICI</a> ou sur Identification dans le menu du haut
		  <br>
		  <br>
</td>
	    </tr>
</table>
	</body>
</html>
