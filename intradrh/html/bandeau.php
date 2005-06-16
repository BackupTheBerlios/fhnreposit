<?
	require("infos.php");
	$lc_prmev[aff_pop]="N";	
	InitPage(false); // initialise ss envoyer les balises de début <HTML> etc ...
	JSpopup(530,500); 

?>
<html>
	<head>
		<title></title>
		<base target="navigation"
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<script language="JavaScript1.2">
			//fonction qui permet de changer la couleur des cellules du menus onmouseover
			function setPointer(theRow, thePointerColor)
				{
					theRow.style.backgroundColor = thePointerColor;
				}					

		</script>
		<!-- definition des styles utilisés sur cette page -->
		<style type="text/css">

			body { font-size: 10px; line-height: 10px; font-family: Arial, Helvetica, Geneva, Swiss, SunSans-Regular; word-spacing: 0.4px; letter-spacing: 0.4px }			
			table { font-size: 10px; line-height: 10px; font-family: Arial, Helvetica, Geneva, Swiss, SunSans-Regular; word-spacing: 0.4px; letter-spacing: 0.4px }

			.Menus_whitebold {
				background-color: #663399;
				line-height: 11px; 
				font-size:11px;
				font-weight: bold;
				color: #f4f4f4;
				padding-bottom: 2px;
				padding-left: 5px;
				padding-right: 5px;
				padding-top: 2px}
				
		
			.backviolet_boldwhite {
				background-color: #2C0068;
				font-weight: bold;
				font-size:18px;
				line-height: 20px; 
				color: #FFFFFF;
				padding-bottom: 2px;
				padding-left: 5px;
				padding-right: 5px;
				padding-top: 2px}
				
			.utilisateur {
				background-color: #2C0068;
				font-weight: bold;
				line-height: 11px; 
				font-size:9px;
				color: #FFFFFF;
				padding-bottom: 2px;
				padding-left: 5px;
				padding-right: 5px;
				padding-top: 2px}
			
			.infover {
				font-size:8px;			}

			a:active  { color: white; text-decoration: none }
			a:link { color: white; text-decoration: none }
			a:visited { color: white; text-decoration: none }
				
</style>
</head>

	<body>			
		<table width="800" border="0" cellpadding="0" cellspacing="0">
			  <!--images et texte du haut-->
			  <tr> 
				<td width="109"><img src="../images/haut_gauche2<?=RetEFAH()?>.gif" width="109" height="78"></td>
				<td width="353"><img src="../images/haut_milieu2.gif" width="353" height="78"></td>
				<!--ici on ecrit le login et le profil de la personne connectée-->
				<td width="500" align="center" valign="middle" class="backviolet_boldwhite">Intranet DRH
<br>
				<span class="utilisateur">
				Utilisateur connecté : <?=$ss_InfoUser[PRENOM]." ".$ss_InfoUser[NOM];?> <br>
				Profil : <?=$ss_InfoUser[LLPROFIL];?></span><br>
<span class="infover">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Version <a href="#" onclick="popup('infover.php');return(false);"><?=$VerNoDRH?></a></span>
				</td>
			  </tr>
			  <tr> 
				<td valign="top" rowspan="2" width="109"><img src="../images/bas_gauche1.gif" width="109"></td>
				<td width="1" height="1" colspan="2"><img src="../spacer.gif" width="1" height="1"></td>
			  </tr>
			  <tr>
				<td colspan="3" align="left" >
					<table border="0" cellpadding="0" cellspacing="0">
						<tr> 
				  <!--ici on met en place le spacer pour marge blanche à gauche du menus-->
						  <td width="1" height="28"><img src="../spacer.gif" width="1" height="1"></td>
						  <?
						  /* ici on met en place les differents menus correspondant au profil de la personne */
						  $resmenus=msq("SELECT * from ENV_MENUS where (MEN_COPROFILS LIKE '%$ss_InfoUser[COPROFIL]%' OR MEN_COPROFILS LIKE '%*%') AND MEN_NBNIVEAU=1 ORDER BY MEN_ORDREAFF");
						  while ($rwm=mysql_fetch_array($resmenus))
								{
								//on met tout ça dans une cellule du tableau
								?><td align="center" valign="middle" nowrap class="Menus_whitebold" onMouseOver="setPointer(this, '#2C0068')" onMouseOut="setPointer(this, '#663399')"><a href="<?=$rwm[MEN_LLURL]?>" title="<?=$rwm[MEN_LLBULLE]?>"><?=$rwm[MEN_LLMENUS]?></a></td>
								<?
								} ?>
						  <!--on fini avec l'image de la chute du bandeau-->
						  <td align="right" valign="bottom" ><img src="../images/bas_droit.gif" height="28"></td>
						</tr>
					</table>
				</td>
			  </tr>
			</table>						
	</body>
</html>
