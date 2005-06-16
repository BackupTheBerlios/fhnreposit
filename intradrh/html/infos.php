<? //infos techniques INTRANET DRH
require_once("fonctions.php");
$VerNoDRH=" 0.200 du 10/06/05";
$debug=false;
$authType="ldapsweb"; 
//$authType="mysql"; 
$DBHost="localhost";
$DBUser="root";
$DBPass="";
$DBDRHName="INTRADRH";
$ChemPYA="../../intradmin/pya/";
$NbLigPPP=10; // nbre de lignes de personnes affichées par page
$NbRepMx=100;
$NbLigFHB=5; // nbre de lignes de personnes au dela desquelles on affiche les flêches Haut et Bas
// Nom de la table de description des autres
// la êm que PYA, mais ça pourrait changer
$TBDRHname="DESC_TABLES";
// nom du champ contenant les caractéristiques globales à la table
$NmChDRHT="TABLE0COMM";
$COPROANO="ANO"; // code profil du User anonyme 
$MaxFSize="100000"; // taille max des fichiers joints !!
$frm2=true; // affichage en frames ou pas

function InitPage($bool_head,$TitreHT="",$chemsup="") {
	DBDRH_connect();
	sess_start();
	include ("globvar.inc");
	if ($bool_head) { ?>
	<html>
	<head>
		<title><?=$TitreHT?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="<?=$chemsup?>../../intranet/partage/css/styles.css" rel="stylesheet" type="text/css">
		<link href="<?=$chemsup?>drh.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	<? } // fin d'envoi des header HTML
	if ($debug) DispDebug();
}

//fonction qui connecte à la base de données
function DBDRH_connect() {
include ("globvar.inc");
mysql_connect($DBHost,$DBUser, $DBPass) or die ("Impossible de se connecter au serveur $DBHost (user: $DBUser, passwd: $DBPass)");
mysql_select_db($DBDRHName) or die ("Impossible d'ouvrir la base de données $DBDRHName.");
}

// fonction qui démarre la session, et qui regarde si certainses variables sont OK
function sess_start($arg_clean="") {
	include ("globvar.inc");
	session_start();
	
	if ($lc_clean==1 || $arg_clean==1) 
	  {
	  if (session_id()!="") {
		//session_destroy(); // détruit la session
		  session_unset(); //détruit toutes les variables de session couramment enregistrées
		}
   	  unregvar("ss_InfoUser");
	  } 
	
	if (!isset($ss_InfoUser[NUPERS])) { // si personne n'est connecté
//		session_start(); //redémarre une session
		$ss_InfoUser[NUPERS]=0; // anonyme
		$ss_InfoUser[TITRE]=""; // anonyme
		$ss_InfoUser[PRENOM]=""; // anonyme
		$ss_InfoUser[NOM]="Anonyme"; // anonyme
		$ss_InfoUser[COPROFIL]="ANO"; // anonyme
		//mysql_connect($DBHost,$DBUser, $DBPass) or die ("Impossible de se connecter au serveur $DBHost (user: $DBUser, passwd: $DBPass)");
		//mysql_select_db($DBDRHName) or die ("Impossible d'ouvrir la base de données $DBDRHName.");
		//$ss_InfoUser[LLPROFIL]= RecupLib("ENV_PROFIL","PRO_COPROFIL", "PRO_LLPROFIL", $COPROANO);
		$ss_InfoUser[LLPROFIL]="Anonyme";
		session_register("ss_InfoUser");
	}
	  
	if (isset($lc_prmev)) { // tableau des paramètres d'environnement
		foreach ($lc_prmev as $key => $val) {
		   // ne MAJ que les nouveaux envoyés
		   $ss_prmev[$key]=$val;
		   }
	   session_register("ss_prmev");
	} // fin si il existe des param d'environnement
} // fin fonction sess_start

// function qui affiche un titre à la charte en pseudo image
function EchoTitIm1($titre,$ifs="") { ?>
<table border="0" cellpadding="0" cellspacing="0" style="line-height: 2">
<tr><td width="42"><img src="../../../intranet/partage/IMAGES/bd_gauche<?=$ifs?>.gif" border="0"></td>
<td height="13" class="txtintbredfbbold" nowrap><?=$titre?></td>
<td width="44"><img src="../../../intranet/partage/IMAGES/bd_droite<?=$ifs?>.gif" border="0"></td></tr>
</table>
<? }

// fonction d'affichage de débogage
function DispDebug() {
  global $HTTP_POST_VARS;
  echo "<B>! MODE DEBOGGAGE ! - </b><BR>";
  echovar ("DB_NAME"); 
  echovar("ss_prmev","yes");
  echo "<PRE><u>Chaine de session:</u> ".session_encode()."</PRE>";
  if (isset($HTTP_POST_VARS)) {
	  echo "<u>Tableau des variables POST : </u><br>\n";
	  	foreach ($HTTP_POST_VARS as $NmVar=>$ValVar) {
			echo "Nom: $NmVar ; valeur : $ValVar <br>\n";
			}
	}
  if (isset($HTTP_GET_VARS)) {
  echo "<u>Tableau des variables GET : </u><br>\n";
  	foreach ($HTTP_GET_VARS as $NmVar=>$ValVar) {
		echo "Nom: $NmVar ; valeur : $ValVar <br>\n";
		}
	} 
} // fin fonction 

// fonction instancie un nouvel objet dans le tableau $CIL pour chaque champ "COMPLEXE"
// que l'on veut afficher et  l'initialise
function CNPYAL ($NTBL,$NMCHP,$TypEdit="C") {
	global $CIL,$DBDRHName;
	 $CIL[$NMCHP]=new PYAobj();
	 $CIL[$NMCHP]->NmBase=$DBDRHName;
	 $CIL[$NMCHP]->NmTable=$NTBL;
	 $CIL[$NMCHP]->NmChamp=$NMCHP;
	 $CIL[$NMCHP]->InitPO();
	 $CIL[$NMCHP]->TypEdit=$TypEdit;
}

// fonction qui affiche une ligne du tablleau dans un formulaire de requete
function DIspLigReq() {
global $FCobj; // objet
$FCobj->InitPO(); // initialise l'objet
echo "<tr><td>\n";
echo "<b>".$FCobj->Libelle."</b>\n";
echo ($FCobj->Comment!="" ? "<br><span class=\"legendes9px\">".$FCobj->Comment."</span>" : "");
echo "</td><td>\n";
$FCobj->EchoFilt(false); // affiche filtre sans négation 
echo "</td></tr>\n";
}
// fonction qui affiche une ligne de tableau
// AFfiche le champ toujours en édition, et en consult uniquement si valeur non vide
// FTE=Force Type Edit (ne tiens pas compte de ce qu'il y a ds l'objet)
function EchoLig($NmChamp,$FTE=""){
	global $CIL,$ss_InfoUser;
	// FTE= Force Type Edit
	if ($FTE!="") $CIL[$NmChamp]->TypEdit=$FTE;
	if ($CIL[$NmChamp]->TypEdit!="C" || $CIL[$NmChamp]->ValChp!="") { 
	  	echo "<tr><td>".$CIL[$NmChamp]->Libelle;
		if ($CIL[$NmChamp]->TypEdit!="C" && $CIL[$NmChamp]->Comment!="") {
			echspan("legendes9px","<BR>".$CIL[$NmChamp]->Comment);
			} 
		echo "</td>\n";
		echo "<td>";
	  	// traitement valeurs avant MAJ
  	  	$CIL[$NmChamp]->InitAvMaj($ss_InfoUser[NUPERS]);
		$CIL[$NmChamp]->EchoEditAll(); // pas de champs hidden
		echo "</td></tr>\n";
	}
}

// fonction qui instancie et initialise ts les objets PYA d'une requete
// récupère directement les noms de champ et de table
function InitObjsReq($req,$TypEdit="C") {
global $CIL,$debug;  
  $tbValChp=mysql_fetch_row($req); // tableau des valeurs de l'enregistrement
  for ($i=0;$i<mysql_num_fields($req);$i++) {
  	  $NmChamp=mysql_field_name($req,$i);
	  CNPYAL(mysql_field_table($req,$i),$NmChamp,$TypEdit); // instancie nvel objet $CIL[NomChamp] et l'initialise
	// MAJ la valeur que si pas nouvel enregistrement
	  if ($TypEdit!="") $CIL[$NmChamp]->ValChp=$tbValChp[$i];
	  if ($debug) EchoLig($NmChamp);
	  } // fin boucle sur les champs du résultat
}	  

// fonction test si un profil a le droit de visualiser les infos DRH
function DispInfDRH($CoProfil) {
switch ($CoProfil) {
	case "SADMIN";   
  	case "DRH_LS"; 
  	case "DRH_ADM";  
  	case "UF_ADM";
  	case "UF_LS";
	$res=true;
	break;

	default;
	$res=false;
	break;  
	}
return ($res);
}

// fonction renvoyant une chaine contenant les codes positions affichables dans l'annuaire séparés par des :
function Ctbica() { 
	$rqca=msq("select TAC_COTACT from TYPE_ACTIVITE where TAC_AFFANN='O'");
	while ($rwca=mysql_fetch_row($rqca)) $tbica.=$rwca[0].":";
	return(vdc($tbica,1));
}
// fonction renvoyant une chaine contenant les codes positions tq la personne appartient à l'EPA séparés par des :
function Ctbicepa() { 
	$rqca=msq("select TAC_COTACT from TYPE_ACTIVITE where TAC_APPEPA='O'");
	while ($rwca=mysql_fetch_row($rqca)) $tbica.=$rwca[0].":";
	return($tbica);
//	return(vdc($tbica,1));
}

// fonction REENTRANTE qui renvoie tous les UF de dépendance d'une UF sous forme de tableau
function TbUFdep($NUUF) {
if ($NUUF!="%") { 
	$TbUF[]=$NUUF;
	$rqUF=msq("select UFO_NUUNITE,UFO_COUFOSUP from UNITE_FONCTION where UFO_COUFOSUP='$NUUF'");
	while ($rpUF=mysql_fetch_array($rqUF)) {
		// ne lance la boucle que si pas déjà dedans
		if (!in_array($rpUF[UFO_NUUNITE],$TbUF)) $TbUF=array_merge($TbUF,TbUFdep($rpUF[UFO_NUUNITE]));
		}
	}
else { // si %, toutes les UF
$rqUF=msq("select UFO_NUUNITE from UNITE_FONCTION group by UFO_NUUNITE");
	while ($rpUF=mysql_fetch_array($rqUF)) $TbUF[]=$rpUF[UFO_NUUNITE];
	}
return($TbUF);
}
?>
