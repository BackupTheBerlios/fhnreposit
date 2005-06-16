<?
	require("infos.php");	
	InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...
?>
<script language="JavaScript" type="text/JavaScript">
	//fonction qui verifie que le login et le password sont bien renseignés
	function verif_form(theform)
		{
			if (theform.login.value == "" )
				{
					alert ("La saisie de l\'identifiant est obligatoire");
					return false;

				}
			else if (theform.password.value == "") {
					alert ("La saisie du mot de passe est obligatoire");
					return false;
			}
			else
				document.theform.submit();				
		}
</script>
<? if ($debug) 	DispDebug();?>

<form name="theform" method="post" target="_parent" action="verif_id.php" onsubmit="return verif_form(this);">
	<table width="71%" border="0" align="center">
	    <tr> 
	      <td width="9%" height="56"><img src="../images/tiret_droite.gif" width="32" height="5"></td>
	      <td colspan="3" class="titrered14px"><p align="left">IDENTIFICATION DE L'UTILISATEUR </p></td>
	    </tr>
<? if ($ss_InfoUser[errlogin]!="") { // erreur d'id?>
	    <tr> 
	      <td>&nbsp;</td>
	      <td width="11%" class="boldred11px">Erreur !</td>
	      <td width="44%" class="normalred11px"><?=$ss_InfoUser[errlogin]?></td>
	      <td width="36%" rowspan="2" nowrap>&nbsp;</td>
	    </tr> 
		<? } ?>
	    <tr> 
	      <td>&nbsp;</td>
	      <td width="11%" class="stylebold11px">Identifiant :</td>
	      <td width="44%"><input name="vf_login" type="text" id="login" size="25" maxlength="25"></td>
	      <td width="36%" rowspan="2" nowrap>&nbsp;</td>
	    </tr>
	    <tr> 
	      <td height="22">&nbsp;</td>
	      <td nowrap class="stylebold11px">Mot de passe :</td>
	      <td><input name="vf_password" type="password" id="password" size="25" maxlength="25"></td>
	    </tr>
<!-- 	    <tr> 
	      <td height="30">&nbsp;</td>
	      <td colspan="3"><span class="normalblack11px">Rq : cliquer <a href="verif_id.php?vf_login=ANO&vf_password=ANONYMOUS" target="_parent"> ICI</a> pour vous (re)connecter en </span><span class="normalred11px">Utilisateur Anonyme</span></td>
	    </tr>
 -->	    <tr> 
	      <td height="30">&nbsp;</td>
 	      <td colspan="3"><span class="normalblack11px">Rq : cliquer <a href="index.php?lc_clean=1" target="_top"> ICI</a> pour vous (re)connecter en </span><span class="normalred11px">Utilisateur Anonyme</span></td>
	    </tr>
	    <tr> 
	      <td height="30">&nbsp;</td>
	      <td colspan="3" nowrap><span class="normalblack11px">Pour obtenir un mot de passe, il faut vous adressez à la <a href="mailto:support@haras-nationaux.fr">Direction Informatique</a></span></td>
	    </tr>
	    <tr> 
	      <td height="46" colspan="4" align="center"><input type="image" src="../images/valider.gif" border="0"></a></td>
	    </tr>
  	</table>
</form>
</body>
</html>
