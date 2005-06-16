<?
require("infos.php");
InitPage(false); // initialise ss envoyer les balises de début <HTML> etc ...

$NmChpId=($authType=="ldapsweb" ? "PER_LCIDLDAP" : "PER_LCIDPERS");
$NUPERS=RecupLib("PERSONNE",$NmChpId,"PER_NUPERS", $vf_login);
$authOK=false; // init var auth OK
if ($NUPERS) { // id existe, on sait pas encore si le profil/passwd sont OK
	if ($authType=="ldapsweb") {
		require("ldapsweb_fct.php"); // permet l'accèsau service web d'authentification
		$parametres[uid]=$vf_login;
		$parametres[passwd]=$vf_password; // l'encryption est faite dans le service
		$parametres[encrypt]=false; // n'est PAS facultatif
		$parametres[code_appli]="sdm2g"; 
		// appel service web
		$ldapauth=auth($parametres);
		
		if ($ldapauth[ConnOk]) {
			$ret = true; // found login/password
			$ldapauth[Nom]=addslashes($ldapauth[Nom]);
			$ldapauth[Prenom]=addslashes($ldapauth[Prenom]);
			$res=RecupLib("ENV_POSSEDE","POS_NUPERS", "POS_COPROFIL", $NUPERS);
			if ($res) { // c'est OK et il n'y a en fait rien à mettre à jour
				$authOK=true;
			} else { // si le user n'existe pas dans la table POSSEDE, c'est qu'il n'a pas les droits
				$ss_InfoUser[errlogin]="Aucun profil spécifique n'existe pour cette personne, ou le mot de passe est incorrect";	
			} // Maj table locale
		} else {
			$ss_InfoUser[errlogin]="Identification via LDAP incorrecte";
			} // fin si profil n'existe pas ou passwd faux
			
	} else { // authentification "à l'ancienne"
	
		$rpid=msq("SELECT * from ENV_POSSEDE where POS_NUPERS=$NUPERS AND POS_LMPASSWD='$vf_password'");
		if (mysql_num_rows($rpid)>0) {
			$authOK=true;
			} // fin si profil existe et passwd OK
		else {
			$ss_InfoUser[errlogin]="Aucun profil spécifique n'existe pour cette personne, ou le mot de passe est incorrect";
			} // fin si profil n'existe pas ou passwd faux
	} // fin test type d'auth
	if ($authOK) {
		$ss_InfoUser[COPROFIL]=RecupLib("ENV_POSSEDE","POS_NUPERS", "POS_COPROFIL", $NUPERS);
		$ss_InfoUser[LLPROFIL]=RecupLib("ENV_PROFIL","PRO_COPROFIL", "PRO_LLPROFIL", $ss_InfoUser[COPROFIL]);
		if ($ss_InfoUser[COPROFIL]=="UF_ADM" || $ss_InfoUser[COPROFIL]=="UF_LS") {// Si le user est administrateur d'UF
			$ss_InfoUser[NUUNITEG]=RecupLib("ENV_POSSEDE","POS_NUPERS", "POS_NUUNITE", $NUPERS); 
			$ss_InfoUser[LLPROFIL].=" ".RecupLib("UNITE_FONCTION","UFO_NUUNITE","UFO_LLUNITE",$ss_InfoUser[NUUNITEG]);
			}
		else { // sinon récupère l'UF de rattachement de la personne
			$ss_InfoUser[NUUNITEG]=RecupLib("INFOS_DRH","DRH_NUPERSO", "DRH_NUUNITE", $NUPERS); 
			}
			
		$ss_InfoUser[NUPERS]=$NUPERS; 
		$ss_InfoUser[TITRE]=RecupLib("PERSONNE","PER_NUPERS", "PER_LMTITREPER", $NUPERS);
		$ss_InfoUser[PRENOM]=RecupLib("PERSONNE","PER_NUPERS", "PER_LLPRENOMPERS", $NUPERS);
		$ss_InfoUser[NOM]=RecupLib("PERSONNE","PER_NUPERS", "PER_LLNOMPERS", $NUPERS);
		$ss_InfoUser[errlogin]=""; // pas d'erreur
	}	
} //fin si id existe
else {
	$ss_InfoUser[errlogin]="Cet identifiant ne correspond à aucune personne de la base";
} // fin test Id NUPERS exista dans la base

//echovar("ss_InfoUser");
session_register("ss_InfoUser");
header ("location: index.php?nav=".($ss_InfoUser[errlogin]!="" ? "identification.php&arg_clean=1" : "navigation.php&arg_clean=no"));
?>
