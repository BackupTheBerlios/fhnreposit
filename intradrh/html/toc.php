<?
	require("infos.php");	
	InitPage(false); // initialise en envoyant les balises de début <HTML> etc ...
?>
<html>
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../intranet/partage/css/styles.css" rel="stylesheet" type="text/css">
<base target="<?=($frm2==true ? "contenu" : "_self")?>">
<style type="text/css">
	.lien
		{				
			background-color: #FFFFFF;
			font-family: Arial, Helvetica, Geneva, Swiss, SunSans-Regular;
			font-size:11px;
			font-weight: normal;
			color: #990033;
			line-height: 10px; 
			padding-bottom: 3px;
			padding-left: 10px;
			padding-right: 5px;
			padding-top: 3px
		}
						
	a:hover { color: #990033 }
	a:active  { color: #990033 }
	a:link { color: #990033 }
	a:visited { color: #990033 }		
</style>
</head>
<body bgcolor="#ffffff" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" STYLE="scrollbar-base-color:#330066 ;scrollbar-arrow-color:#FFFFFF;
scrollbar-Track-Color:#6C30AC">
<?
$SpM=10; // espace vertical en pixels entre les menus
// titre de la rubrique ppale courante
$WidthTot=145;
 ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td colspan="2" class="normalred11px" style="padding-left: 5px"><?=$titrubp=RecupLib("ENV_MENUS","MEN_NUMENUS","MEN_LLBULLE",$nrp0);?></td></tr>

<? // menus de niveau 2
// $ss_InfoUser[COPROFIL] code profil user
// $nrp0 n° de la rubrique parente


$rsMn2=msq("SELECT * from ENV_MENUS where (MEN_COPROFILS LIKE '%$ss_InfoUser[COPROFIL]%'  OR MEN_COPROFILS LIKE '%*%') AND MEN_NBNIVEAU=2 AND MEN_NUPARENT=$nrp0 ORDER BY MEN_ORDREAFF");
while ($rwm2=mysql_fetch_array($rsMn2)) {
	?>
	<!-- spacer au-dessus de chaque rubrique width="<?=$WidthTot-30?>"-->
	<tr><td><img src="../images/spacer.gif" border="0" height="<?=$SpM?>"></td>
	<td width="30"><img src="../images/spacer.gif" border="0" height="<?=$SpM?>"></td>
	</tr>
	<tr><td height="14" class="txtintbredfbbold" nowrap title="<?=$rwm2[MEN_LLBULLE]?>"><?=$rwm2[MEN_LLMENUS]?></td><td height="14"><img src="../images/titre_bordure2.gif" border="0" width="30" height="14" alt="<?=$rwm2[MEN_LLBULLE]?>"></td>
	</tr>
	<? // niveaux 3 (liens)
	$rsMn3=msq("SELECT * from ENV_MENUS where (MEN_COPROFILS LIKE '%$ss_InfoUser[COPROFIL]%' OR MEN_COPROFILS LIKE'%*%') AND MEN_NBNIVEAU=3 AND MEN_NUPARENT=$rwm2[MEN_NUMENUS] ORDER BY MEN_ORDREAFF");
	while ($rwm3=mysql_fetch_array($rsMn3)) { ?>
	<tr><td colspan="2" height="14" class="lien">&#149; <a href="<?=$rwm3[MEN_LLURL]?>" title="<?=$rwm3[MEN_LLBULLE]?>"><?=$rwm3[MEN_LLMENUS]?></a></td></tr> 	
<?	} // fin boucle menu niveau 3
} // fin boucle menu niveau 2
?>
</table>
</body>
</html>
