<?
/* FICHIER DE FONCTIONS */
// PARTAGE PAR TOUTES LES APPLIS
// quelques variables globales
$nbcarmxlist=50; // nbre de caract�res max affich�s par cellules dans les tableaux liste
$nbligpp_def=15; // nbre de lignes affich�es par page par d�faut
// servant ds les progs d'�dition
$nbrtxa=5; // nbre de lignes des textarea
$nbctxa=40; // nbre de colonnes des textarea
$nValRadLd=4; // nbre de valeurs passage liste d�roulante/boutons radio case � cocher
$SzLDM=6; // parmetre size pour les listes d�roulantes multiples
$VSLD="#SEL#"; // caract�res ins�r� en d�but de valeur de listes indiquant la s�lection
$carsepldef="-"; // caract�re par d�faut s�parant les valeur dans les listes d�roulantes
$CSpIC=""; // caract�re pour "isoler" les noms de champs merdiques
// ne fonctionne qu'avec des versions r�centes de MySql
$MaxFSizeDef="100000"; // taille max des fichiers joints par d�faut!!

// Nom de la table de description des autres
$TBDname="DESC_TABLES";
// nom du champ contenant les caract�ristiques globales � la table
$NmChDT="TABLE0COMM";

$ListTest="linux xsir-intralinux 126.0.26.2";
$ListDev="linuxk6 192.168.0.20 192.168.0.30";

// NECESSITE D'IMPLEMENTER LES FONCTIONS D'ACCES A L'ANNUAIRE
require_once ("funct_sso.inc");
// et tt ce qui concerne l'objet PYA
require_once("PYAObj_def.inc");
require_once("debug_tools.inc"); // fonctions servant au debogage


// fonction qui retourne une ext fonction de l'adresse de l'H�te
function RetEFAH($UC=false) {
global $ListTest,$ListDev,$HTTP_HOST;
$HostName=($HTTP_HOST=="" ? $_SERVER["HTTP_HOST"] : $HTTP_HOST);
if (stristr($ListTest,$HostName)) {
   return ($UC ? "!TEST_" : "_test"); }
else if (stristr($ListDev,$HostName)) {
   return ($UC ? "!LOC_" : "_loc"); }
else return ("");
}

// test si une chaine correspond � un fichier image
// ie si son nom contient l'extension .gif, .jpeg, etc ...
function TestNFImg($Nmf){
return(strstr(strtolower($Nmf),".gif") or
     strstr(strtolower($Nmf),".jpg") or
     strstr(strtolower($Nmf),".png") or
     strstr(strtolower($Nmf),".jpeg"));
}

// conversion d'une date en fran�ais jj/mm/aa vers anglais aa-mm-jj
function DateA($DateOr){
$tab=explode("/",$DateOr);
$tab[0]=$tab[0]+0;
$tab[1]=$tab[1]+0;
$DateOr=$tab[2]."-".$tab[1]."-".$tab[0];
return($DateOr);
}
// fonction inverse (anglais vers fran�ais)
function DateF($DateOr){
$tab=explode("-",$DateOr);
$tab[0]=$tab[0]+0;
$tab[1]=$tab[1]+0;
$DateOr=$tab[2]."/".$tab[1]."/".$tab[0];
return($DateOr);
}
// fonction qui vire les x derniers car d'une chaine
function vdc($strap,$nbcar) {
return (substr($strap,0,strlen($strap)-$nbcar));
}

// fonction qui renvoie x espaces ins�cables
function nbsp($i=1){
return(str_repeat("&nbsp;",$i));
}

// connection et s�lection �ventuelle d'une base
function msq_conn_sel($Host,$User,$Pwd,$DB="") {
     mysql_connect($Host,$User,$Pwd) or die ("Impossible de se connecter au serveur $Host avec le user $User, passwd: ***** ");
if ($DB!="") mysql_select_db($DB) or die ("Impossible d'ouvrir la base de donn�es $DB.");}

// fonction qui effectue une requete mysql, et affiche une erreur avec la requete si necessaire
function msq($req,$lnkid="",$mserridrq="") {
  $messret="<BR><BR><a href=\"javascript:history.back()\">RETOUR</A> � la page pr�c�dente";
  if ($lnkid=="") { // connection par la connexion courante
    $ret=mysql_query($req) or die("<U>Requete mysql invalide</U> : <I>$req</I><BR>$mserridrq<BR><U>Erreur mysql</U>:     <I>".mysql_error()."</I>".$messret);}  else
    $ret=mysql_query($req,$lnkid) or die("<U>Requete mysql invalide</U> : Id de connection =$lnkid,requ�te= <I>$req</I><BR>$mserridrq<BR><U>Erreur mysql</U>:<I>".mysql_error()."</I>".$messret);
return $ret;}

// fonction qui echoise un texte dans un style
function echspan($style,$text) {
         echo "<span class=\"$style\">$text</span>";
}

// fonction qui echoise un champ n
function echochphid($NmC,$ValC) {
         echo "<input type=\"hidden\" name=\"$NmC\" value=\"$ValC\">\n";
}

// finction qui v�rifie qu'une adresse mail est valide
// ie un @, pas d'espaces et pas de retour chariots
function VerifAdMail($admail) {
         if (strstr($admail,"@") && !strstr($admail," ")  && !strstr($admail,"\n"))
                  { return (true) ;}
         else return(false);
}

// fonction qui affiche du HTML customis� fonction d'une chaine de car
function DispCustHT($Val2Af) {
   // si dans la chaine il y a un @, pas d'espaces ni de retour chariot, alors c'est une adressemail 
   if (VerifAdMail($Val2Af))
      {
      $Val2Af="<A HREF=\"mailto:".$Val2Af."\">".$Val2Af."</a>";
      }
  else if (strstr($Val2Af,"http://")  && !strstr($Val2Af,"\n"))
      {
      $Val2Af="<A HREF=\"".$Val2Af."\" target=\"_blank\">".$Val2Af."</a>";
      }
  else if (strstr($Val2Af,"www.")  && !strstr($Val2Af," ") && !strstr($Val2Af,"\n"))
      {
      $Val2Af="<A HREF=\"http://".$Val2Af."\" target=\"_blank\">".$Val2Af."</a>";
      }
  else {  // sinon traitement divers
      $Val2Af=ereg_replace("\n","<br>", $Val2Af);
      $Val2Af=ereg_replace("<","&lt;", $Val2Af);
      $Val2Af=ereg_replace(">","&gt;", $Val2Af);
      $Val2Af=($Val2Af=="" ? "&nbsp;" : $Val2Af);
      }
return ($Val2Af);
}

// fonction d'effacement d'un fichier s'il existe
function delfich($ChemFich) {
  // echo "Chemin complet du fichier a effacer :$ChemFich<BR>";
  if (file_exists($ChemFich)) unlink ($ChemFich);
  }

// fonction qui r�cup�re un libell� dans une table fonction de la cl�
// sert aussi � tester si un enregistrement existe (renvoie faux sinon)
function RecupLib($Table, $ChpCle, $ChpLib, $ValCle,$lnkid="",$wheresup="") {
$wheresup=($wheresup!="" ? " AND ".$wheresup : "");
$req="SELECT $ChpCle, $ChpLib FROM $CSpIC$Table$CSpIC WHERE $ChpCle='$ValCle' $wheresup";
$reqRL=msq($req,$lnkid) or die("Requete sql de RecupLib invalide : <I>$req</I>".($lnkid=="" ? ""
:$lnkid));if (mysql_num_rows($reqRL)>0) {
  $resRL=mysql_fetch_row($reqRL);
  return($resRL[1]);
  }
else return (false);
}

// fonction qui r�cup�re les champ libell� (0) ou commentaire(1) d'une table
function RecLibTable($NM_TABLE,$offs) {
global $TBDname,$NmChDT;
$req="SELECT LIBELLE,COMMENT FROM $CSpIC$TBDname$CSpIC WHERE NM_TABLE='$NM_TABLE' AND NM_CHAMP='$NmChDT'";$reqRL=mysql_query($req) or die("Requete mysql de RecLibTable invalide : <I>$req</I>");$resRL=mysql_fetch_row($reqRL);
return($resRL[$offs]);
}

/* fonction de traitement des champs li�s
 arg1: chaine brute de liaison, arg2: valeur cherch�e (optionnelle)
 la chaine de liaison comporte 2 parties:
 Nom_base,nom_serveur,nom_user,passwd;0: table, 1: champ li� (cl�); 2: ET SUIVANTS champs affich�s

retourne un tableau associatif si valc="", une valeur sinon
A priori, $reqsup avait �t� impl�ment� pour la gestion de projet, mais n'est plus utilis�*/

function ttChpLink($valb0,$reqsup="",$valc=""){
global $DBHost,$DBUser,$DBName,$carsepldef,$TBDname;
//$valb0=str_replace (' ','',$valb0); // enl�ve espaces ind�sirables
$valbrut=explode(';',$valb0);
if (count($valbrut)>1) { // connection � une base diff�rente
  $lntable=$valbrut[1];
  $defdb=explode(',',$valbrut[0]);
  $newbase=true;
 // si user et/ou hote d'acc�s � mysql est diff�rent, on etablit une nvlle connexion Mysql
   if (($defdb[1]!="" && $defdb[1]!=$DBHost)||($defdb[2]!="" && $defdb[2]!=$DBUser)) {
     $lnc=mysql_connect($defdb[1],$defdb[2],$defdb[3]) or die ("Impossible de se connecter au serveur $defdb[1], user: $defdb[2], passwd: ***** ");
	 $newconnect=true;
     }
   mysql_select_db($defdb[0]) or die ("Impossible d'ouvrir la base de donn�es $defdb[0].");
  }
else { //commme avant
   $lntable=$valbrut[0];
   $newbase=false;
   $newconnect=false;
   }
// 0: table, 1: champ li� (cl�); 2: ET SUIVANTS champs affich�s
$defl=explode(',',$lntable);
$nbca=0; // on regarde les suivants pour construire la requete
$rcaf="";
/* si le 1er � afficher champ comporte un & au d�but, il faut aller cherche les valeurs dans une 
table; les param�tres sont  indiqu�s dans les caract�ristiques d'�dition de CE champ dans la table  de d�finition*/
if (strstr($defl[2],"&")) { // si chainage
    $nmchp=substr ($defl[2],1); // enl�ve le &
       if (strstr($nmchp,"@")) { // si classement sur ce champ
         $nmchp=substr ($nmchp,1); // enl�ve le @
         $orderby=" order by $nmchp ";
         }
     $rcaf=$nmchp;
     $rqvc=msq("select VALEURS from $TBDname where NM_CHAMP='$nmchp' AND NM_TABLE='$defl[0]'");
     $resvc=mysql_fetch_row($rqvc);
     $valbchain=$resvc[0];
    }
else {
     while ($defl[$nbca+2]!="") {
       $nmchp=$defl[$nbca+2];
       if (strstr($nmchp,"!")) { // caract�re sp�rateur d�fini
         $nmchp=explode("!",$nmchp);
       $tbcs[$nbca+1]=$nmchp[0]; // s�parateur avant le "!"
       $nmchp=$nmchp[1];
         }
       if (strstr($nmchp,"@")) { // si classement sur ce champ
         $nmchp=substr ($nmchp,1); // enl�ve le @
       $orderby=" order by $nmchp "; 
         }
       $rcaf=$rcaf.",".$nmchp;
       $nbca++;
       }
}
 // soit on cherche 1 et 1 seule valeur
if  ($valc!="") {
    $whsl=" where $defl[1]='$valc'";
    }
// soit la liste est limit�e par une clause where suppl�mentaire
else {
     $whsl=$reqsup;
     }
$rql=msq("SELECT $defl[1] $rcaf from $defl[0] $whsl $orderby");
// constitution du tableau associatif � 2 dim de corresp code ->lib
//echo "<!--debug2 rql=SELECT $defl[1] $rcaf from $defl[0] $whsl $orderby <BR>-->";
$tabCorlb=array();
while ($resl=mysql_fetch_row($rql)) {
  //$cle=strtoupper($resl[0]);
	$cle=$resl[0];
	//echo "<!--debug2: $cle\n-->";
  if (isset($valbchain)) { // champ li� � nouveau
     $resaf=ttChpLink($valbchain,"",$cle); // on r�entre dans la fonction et on va chercher dans le champ 
     }
  else { // pas de liaison, on construit
    $resaf=$resl[1];
    for ($k=2;$k<=$nbca;$k++) {
      $cs=($tbcs[$k]!="" ? $tbcs[$k] : $carsepldef);
      $resaf=$resaf.$cs.$resl[$k];
      }
  }
  $tabCorlb[$cle]=$resaf; // tableau associatif de correspondance code -> libell�
  //echo "<!--debug2 cle: $cle; val: $resaf ; valverif:   ".$tabCorlb[$cle]."-->\n";  
  } 
  // fin boucle sur les r�sultats
// retablit les param�tres normaux si n�c�ssaire
if ($newconnect) {
	mysql_close($lnc);
	DBconnect(); // r�ouvre la session normale
	}
if ($newbase) mysql_select_db($DBName) or die ("Impossible d'ouvrir la base de donn�es $DBName.");
if ($valc!="") {
  if ($resaf=="") $resaf="N.C.";
  return ($resaf);
  }
else {
	return($tabCorlb); // retourne le tableau associatif
	}
}

// info serveur
function pinfserv() {
//  echo gethostbyaddr ("127.0.0.1");
  echo gethostbyname ("localhost");
  /*getmxrr("localhost",$mxhosts) ;
  effectue une recherche DNS pour obtenir les MX de l`h�te hostname. Retourne TRUE si des 
enregistrements sont trouv�s, et FALSE si une erreur est rencontr�e, ou si la recherche �choue.
La liste des enregistrements MX est plac�e dans le tableau mxhosts.
	foreach ($mxhosts as $nameh)
     	{ echo $nameh." " ;} */   
}


// fonction qui retourne le type d'un champ
// Utiliser plutot la fonction ShowField qui retourne un tableau avec beaucoup plus d'infos
function mysqft ($NOMC,$NM_TABLE)
{
$resf=msq("select $NOMC from $CSpIC$NM_TABLE$CSpIC LIMIT 0");
return (mysql_field_type($resf,0));
}
// fonction qui retourne les flags d'un champ
// Utiliser plutot la fonction ShowField qui retourne un tableau avec beaucoup plus d'infos
function mysqff ($NOMC,$NM_TABLE)
{
$resf=msq("select $NOMC from $CSpIC$NM_TABLE$CSpIC LIMIT 0");
return (mysql_field_flags($resf,0)); 
}
// fonction qui retourne un tableau de hachage des caract d'un champ
function ShowField($NOMC,$NM_TABLE) {
$table_def = msq("SHOW FIELDS FROM $CSpIC$NM_TABLE$CSpIC LIKE '$NOMC'");
return (mysql_fetch_array($table_def));
}


// fonction qui affiche une liste d�roulante, ou des boutons radio ou cases � cocher
// ceci fonction du nombre de valeurs sp�cifi�es dans la variable globale $nValRadLd
// les valeurs selectionn�es sont pr�c�d�es de la chaine $VSLD
// arguments :
// - un tableau associatif cl�=>valeur
// - le nom du controle
// - s'il est multiple ou non (non par d�faut)
// - 4�me argument (optionel) force  les cases � cocher ou boutons radio qqsoit le nbre de valeur
function DispLD($tbval,$nmC,$Mult="no",$Fccr="") {
global $nValRadLd,$VSLD,$SzLDM;
if (count($tbval)==0) {
   echo "Aucune liste de valeurs disponible <BR>";
   echo "<INPUT TYPE=\"hidden\" name=\"".$nmC."[]\" value=\"\">";
   }
elseif (count($tbval)>$nValRadLd && $Fccr=="") { 
// liste d�roulante: nbre val suffisantes et pas de forcage 
  echo "<SELECT ondblclick=\"document.theform.submit();\" NAME=\"".$nmC;
  $SizeLDM=min($SzLDM,count($tbval));
  echo ($Mult!="no" ? "[]\" MULTIPLE SIZE=\"$SizeLDM\">" : "\">");
  foreach ($tbval as $key =>$val) {
    echo "<OPTION VALUE=\"$key\" ";
    if (strstr($val,$VSLD)) {
      $sel="SELECTED";
      $val=str_replace ($VSLD, "", $val); // retourne la chaine ss le car de s�lection
      }
    else $sel="";
    echo $sel.">$val</OPTION>";
    } // fin boucle sur les valeurs
  echo "</SELECT>";
  echo ($Mult!="no" ? "<br><small>Appuyez sur Ctrl pour s�lectionner plusieurs valeurs</small>" : "");} // fin liste d�roulante
else if ($Mult!="no" && !stristr($Fccr,"RAD") ) // cases � cocher si multiple ou pas de for�age en radio
  { 
  foreach ($tbval as $key =>$val) {
    if ($key!="") {
      echo "<INPUT TYPE=\"CHECKBOX\" NAME=\"".$nmC."[]\" VALUE=\"$key\" ";
      if (strstr($val,$VSLD)) {
        $sel="checked";
        $val=str_replace ($VSLD, "", $val); // retourne la chaine ss le car de s�lection
        }
      else $sel="";
      echo $sel.">".$val;
      echo (stristr($Fccr,"BR") ? "<BR>" : " &nbsp;&nbsp;");
      } // fin si valeur non nulle    
    } // fin boucle sur les valeurs
  } // fin cases � cocher
else {// boutons radio
  foreach ($tbval as $key =>$val) {
    echo "<INPUT TYPE=\"RADIO\" NAME=\"$nmC\"".($Mult!="no" ? "[]" :"" )." VALUE=\"$key\" ";
    if (strstr($val,$VSLD)) {
      $sel="checked";
      $val=str_replace ($VSLD, "", $val); // retourne la chaine ss le car de s�lection
      }
    else $sel="";
    echo $sel.">".$val;
    echo (stristr($Fccr,"BR") ? "<BR>" : " &nbsp;&nbsp;");
    } // fin boucle sur les valeurs
  }// fin boutons radio
} // fin fonction

 
// fonction qui efface une variable de session si elle existe
// et la d�truit par d�faut
function unregvar($var,$annvar=true)
{
if (isset($var)) {
  session_unregister($var);
  if ($annvar) unset($$var); // d�truit par d�faut ensuite
  }
}
// colle le code javascript d'ouverture d'une popup
function JSpopup($wdth=500,$hght=400,$nmtarget="Intlpopup") {
global $HTTP_HOST;
$HostName=($HTTP_HOST=="" ? $_SERVER["HTTP_HOST"] : $HTTP_HOST); // because diff�rentes versions
// on change le nom de target des popups internet (externes) pour ne pas foutre la merde dans les popups ouvertes sur l'intranet
$nmtarget=(strstr($HostName,"haras-nationaux.fr")!=false ? "Ext".$nmtarget : $nmtarget);
?>
<SCRIPT>
// ouverture d'une Popup
var oPopupWin; // stockage du handle de la popup
function popup(page, width, height) {
    NavVer=navigator.appVersion;
	HostName='<?=$HostName?>' // sert au debogage;
    if (NavVer.indexOf("MSIE 5.5",0) == -1 && NavVer.indexOf("MSIE 6.",0) == -1) {
        var undefined;
        undefined='';
        }

    var tmp;
    if (oPopupWin) {
        // Make sure oPopupWin is empty before
        // calling .close() or we could throw an
        // exception and never set it to null.
        tmp = oPopupWin;
        oPopupWin = null;
        // Only works in IE...  Netscape crashes
        // if you have previously closed it by hand
        if (navigator.appName != "Netscape") tmp.close();
      }
  if (width==undefined)
  width=<?=$wdth?>;
  if (height==undefined)
  height=<?=$hght?>;
    oPopupWin = window.open(page, "<?=$nmtarget?>", "alwaysRaised=1,dependent=1,height=" + height + ",location=0,menubar=0,personalbar=0,scrollbars=1,status=0,toolbar=0,width=" + width + ",resizable=1, left=50, top=50");
	oPopupWin.focus();
	// valeur de retour diff�rente suivant navigateur (merdique a souhait) !!!
	var bAgent = window.navigator.userAgent;
	var bAppName = window.navigator.appName;
	if ((bAppName.indexOf("Explorer") >= 0) && (bAgent.indexOf("Mozilla/3") >= 0) && (bAgent.indexOf("Mac") >= 0))
		return true; // dont follow link
	else return false; // dont follow link
	//return !oPopupWin;

}
</SCRIPT>
<?
}
/* colle le code javascript d'ouverture d'une popup Loupe de photo qui se redimensionne automatiquement
Utilisation: appel de cette fonction en php au d�but du fichier dans l'entete <HEAD> pas ex
<? JSPopLoup();?>
ensuite: lien du type <a href="#" onclick="poploup(image_avec_chemin_relatif,titre,commentaire)">
A noter que le chemin relatif de l'image est donn� par rapport au fichier appelant (comme pour une image normale)
*/
function JSPopLoup($nmtarget="Intlpopup") {
// pour assurer compat. avec vieilles versions de php
$doc_root_vm=($_SERVER["DOCUMENT_ROOT"]=="" ? "/home/httpd/html" : $_SERVER["DOCUMENT_ROOT"]);
// on calcule le chemin du fichier appeleant pour pouvoir utiliser des liens relatifs
// i.e. on enl�ve du chemin absolu (getcwd) la racine du serveur
$chemcour=str_replace ( $doc_root_vm,"" , getcwd());
//echo "test chemin:".getcwd()."<br>";
?>
<SCRIPT>
// ouverture d'une Popup Loupe auto redimensionnante
var oPopupWin; // stockage du handle de la popup
function poploup(image,titre,commentaire) {
    NavVer=navigator.appVersion;
    if (NavVer.indexOf("MSIE 5.5",0) == -1 && NavVer.indexOf("MSIE 6.",0) == -1) {
        var undefined;
        undefined='';
        }

    var tmp; // issu d'un copier/coller antediluvien
    if (oPopupWin) {
        // Make sure oPopupWin is empty before
        // calling .close() or we could throw an
        // exception and never set it to null.
        tmp = oPopupWin;
        oPopupWin = null;
        // Only works in IE...  Netscape crashes
        // if you have previously closed it by hand
        if (navigator.appName != "Netscape") tmp.close();
      }
  
    oPopupWin = window.open("", "<?=$nmtarget?>", "alwaysRaised=1,dependent=1,height=200,location=0,menubar=0,personalbar=0,scrollbars=no,status=0,toolbar=0,width=200,resizable=1");    
	oPopupWin.document.open();
	if (titre=="") {titre="Loupe";}
	oPopupWin.document.write("<HTML><HEAD><TITLE>"+titre+"</TITLE></HEAD>\n<BODY>\n");
	oPopupWin.document.write("<CENTER>\n");
	oPopupWin.document.write("<IMG SRC=\"<?=$chemcour?>/" + image+"\"><br>\n");
	if (commentaire!="") {oPopupWin.document.write("<small><I>"+commentaire+"</I></small><br>\n");}
	oPopupWin.document.write("<br><a href=\"javascript:self.close()\" ><IMG SRC=\"/hn0700/partage/IMAGES/fermer.gif\" border=\"0\"></a>\n");
	oPopupWin.document.write("</CENTER>\n"); 
	oPopupWin.document.write("<script language=\"JavaScript\">\n");
	// la fonction d'ajustement n'est pas appel�e directement, mais toutes les 5 sec pour laisser
	// le temps aux images de se charger ;-)
	oPopupWin.document.write("function ajuste() {\n");
	//oPopupWin.document.write("alert('coucou');"); DEBUG
   oPopupWin.document.write("var H = document.body.scrollHeight+50;\n");
	oPopupWin.document.write("var W = document.body.scrollWidth+30;\n");
	oPopupWin.document.write("var SH = screen.height;\n");
	oPopupWin.document.write("var SW = screen.width;\n");
	oPopupWin.document.write("window.moveTo((SW-W)/2,(SH-H)/2);\n");
	oPopupWin.document.write("window.resizeTo(W,H);\n");
	oPopupWin.document.write("} \najuste();"); // appel au premier coup
	oPopupWin.document.write(" \nsetTimeout(\"ajuste()\",2000);");
		oPopupWin.document.write("</sc"+"r"+"ipt>\n"); // astuce sinon �a arrete le script courant 
	oPopupWin.document.write("</bo"+"d"+"y></HT"+"M"+"L>\n"); // idem
	oPopupWin.document.close();
	oPopupWin.focus();    
	return !oPopupWin;
}
</SCRIPT>
<?
}
//
// fonction d'affichage de valeur(s) d'une variable, eventuellement tableau, eventuellement associatif
// la d�tection du format est automatique
function echovar($nom_var,$ass="no",$echov=true) {
global $$nom_var;
if (is_array($$nom_var)) {
  $strres="Tableau".($ass!="no" ? " associatif":"")." \$$nom_var: ";
  if ($ass!="no") { //tableau associatif 
    foreach ($$nom_var as $key=>$val) {
      $strres.= $key."=>".$val.";";
      }
    } // fin si associatif
  else {
    $i=0;
    foreach ($$nom_var as $val) {
      $strres.=$i."=>".$val.";";
      $i++; }
     }
  }
else { // pas tableau
  $strres="Variable \$$nom_var:".$$nom_var." (".gettype($$nom_var).")";
}
if ($echov) 
	{echo $strres."<BR>\n";}
	else return($strres);
} 

function retvar($var2ret,$ass="no",$echov=true) {
if (is_array($var2ret)) {
  $strres="Tableau".($ass!="no" ? " associatif":"")." \$var2ret: ";
  if ($ass!="no") { //tableau associatif 
    foreach ($var2ret as $key=>$val) {
      $strres.= $key."=>".retvar($val,$ass,$echov)."<br>";
      }
    } // fin si associatif
  else {
    $i=0;
    foreach ($var2ret as $val) {
      $strres.=$i."=>".$val.";";
      $i++; }
     }
  }
else { // pas tableau
  $strres="Variable \$var2ret:".$var2ret." (".gettype($var2ret).")";
}
if ($echov) 
	{echo $strres."<BR>\n";}
	else return($strres);
} 


// Fonction de definition de condition
// appel�e pour les def de liste
 function SetCond ($TypF,$ValF,$NegF,$NomChp) {
 if ($ValF!=NULL && $Vaf!="%") {
    switch ($TypF) { // switch sur type de filtrage
      case "INPLIKE" : // boite d'entr�e
        $ValF=trim($ValF);
        if (substr($ValF,-1,1)!="%") $ValF.="%";
        $cond="$NomChp LIKE '".$ValF."'";
        break;

      case "LDM" : // liste � choix multiples de valeurs ds ce cas la valeur est un tableau
                 // la condition r�sultante est omChp LIKE '%Val1%' or NomChp LIKE '%Val2%' etc ...
        if (is_array($ValF)) {  // teste Valf est un tabelau
           foreach ($ValF as $valf) {
             if ($valf=="%" || $valf=="000") {
                $cond="";
                break; // pas de condition s'il y a %
                }
             else
                $cond.="$NomChp LIKE '".$valf."' OR "; // avant on entourait de % la valeur
             }
           if ($cond!="") $cond="(".substr($cond,0,strlen($cond)-4).")"; // vire le dernier OR
                                                          // et rajoute () !!
           } // si ValF pas tableau
        else $cond="";
        break;
        
      case "LDMEG" : // liste � choix multiples de valeurs ds ce cas la valeur est un tableau
       // la condition r�sultante est un NomChp ='Val1' or NomChp ='Val2' etc ...
        if (is_array($ValF)) {  // teste Valf est un tabelau
           foreach ($ValF as $valf) {
             if ($valf=="%" || $valf=="000") {
                $cond="";
                break; // pas de condition s'il y a %
                }
             else
                $cond.="$NomChp='".$valf."' OR ";
             }
           if ($cond!="") $cond="(".substr($cond,0,strlen($cond)-4).")"; // vire le dernier OR  
	   // et rajoute () !!          
	   } // si ValF pas tableau
        else $cond="";

        break;
        
      case "DANT" : // date ant�rieure �
      case "DPOST" : // date ant�rieure �
        if ($ValF=="%" || $ValF=="") break; // pas de condition
        $oprq=($TypF=="DANT" ? "<=" : ">="); // calcul de l'op�rateur
        $cond="$NomChp $oprq '".DateA($ValF)."'";
        break;

      case "DATAP" : // date inf et sup
        if ($ValF[0]!="%" && $ValF[0]!="") $cond="$NomChp >= '".DateA($ValF[0])."'";

        if ($ValF[1]!="%" && $ValF[1]!="") {
           $cond=($cond=="" ? "" : $cond." AND ");
           $cond.="$NomChp <= '".DateA($ValF[1])."'";
           }
        break;
         
      default :
        $cond="";
        break;
      } // fin switch
  } // fin CalF a une valeur coh�rente
  else $cond="";


  if ($cond!="" && $NegF!="") $cond="NOT(".$cond.")"; // negationne �ventuellement
  return($cond);
} // fin fonction SteCond

// fonction renvoyant un tableau d'objets PYA initialis�s en fonction d'une simple requ�t SQL
// les objets sont initialis�s � partir des noms de champs et des noms de base du resultat
function InitPOReq($req,$Base="") {
global $debug, $DBName;
  if ($Base=="") $Base=$DBName;
  $resreq=msq($req." limit 1");
  $tbValChp=mysql_fetch_row($resreq); // tableau des valeurs de l'enregistrement
  for ($i=0;$i<mysql_num_fields($resreq);$i++) {
      $NmChamp=mysql_field_name($resreq,$i);
      $NTBL=mysql_field_table($resreq,$i);
      $CIL[$NmChamp]=new PYAobj(); // nouvel objet
      $CIL[$NmChamp]->NmBase=$DBName;
      $CIL[$NmChamp]->NmTable=$NTBL;
      $CIL[$NmChamp]->NmChamp=$NmChamp;
      $CIL[$NmChamp]->InitPO();
			$strdbgIPOR.=$NmChamp.", ";
    } // fin boucle sur les champs du r�sultat
  if ($debug) echo("Champs trait�s par la fct InitPOReq :".$strdbgIPOR."<br>\n");
  return($CIL);
}

// fonction envoi de mail text+HTML, pomp� sur nexen et bricol� ...
function mail_html($destinataire, $sujet , $messhtml,  $from)
{
$limite = "_parties_".md5 (uniqid (rand()));

$entete = "Reply-to: $from\n";
$entete .= "From:$from\n";
$entete .= "Date: ".date("l j F Y, G:i")."\n";
$entete .= "MIME-Version: 1.0\n";
$entete .= "Content-Type: multipart/alternative;\n";
$entete .= " boundary=\"----=$limite\"\n\n";

//Le message en texte simple pour les navigateurs qui
//n'acceptent pas le HTML
$texte_simple = "This is a multi-part message in MIME format.\n";
$texte_simple .= "Ceci est un message est au format MIME.\n";
$texte_simple .= "------=$limite\n";
$texte_simple .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
$texte_simple .= "Content-Transfer-Encoding: 8bit\n\n";
//$texte_simple .=  "Procurez-vous un client de messagerie qui sait afficher le HTML !!";
$texte_simple .=  strip_tags(eregi_replace("<br>", "\n", $messhtml)) ;
$texte_simple .= "\n\n";

//le message en html original
$texte_html = "------=$limite\n";
$texte_html .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
$texte_html .= "Content-Transfer-Encoding: 8bit\n\n";
$texte_html .= $messhtml;
$texte_html .= "\n\n\n------=$limite--\n";

return mail($destinataire, $sujet, $texte_simple.$texte_html, $entete);
}

// envoi de mail avec pi�ce jointe
// pour l'instant utilis� seulement pour les messages anti-spam
function mail_fj($destinataire,$sujet,$message,$from,$file) {
//----------------------------------
// Construction de l'ent�te
//----------------------------------
// On choisi g�n�ralement de construire une fronti�re g�n�r�e aleatoirement
// comme suit. (REM: je n'en connais pas la raison profonde)
$boundary = "-----=".md5(uniqid(rand()));

// Ici, on construit un ent�te contenant les informations
// minimales requises.
// Version du format MIME utilis�
$header = "MIME-Version: 1.0\r\n";
// Type de contenu. Ici plusieurs parties de type different "multipart/mixed"
// Avec un fronti�re d�finie par $boundary
$header .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
$header .= "\r\n";

//--------------------------------------------------
// Construction du message proprement dit
//--------------------------------------------------

// Pour le cas, o� le logiciel de mail du destinataire
// n'est pas capable de lire le format MIME de cette version
// Il est de bon ton de l'en informer
// REM: Ce message n'appara�t pas pour les logiciels sachant lire ce format
$msg = "Je vous informe que ceci est un message au format MIME 1.0 multipart/mixed.\r\n";

//---------------------------------
// 1�re partie du message
// Le texte
//---------------------------------
// Chaque partie du message est s�par� par une fronti�re
$msg .= "--$boundary\r\n";

// Et pour chaque partie on en indique le type
$msg .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
// Et comment il sera cod�
$msg .= "Content-Transfer-Encoding:8bit\r\n";
// Il est indispensable d'introduire une ligne vide entre l'ent�te et le texte
$msg .= "\r\n";
// Enfin, on peut �crire le texte de la 1�re partie

$msg .= $message."\r\n";
$msg .= "\r\n";

//---------------------------------
// 2nde partie du message
// Le fichier
//---------------------------------
// Tout d'abord lire le contenu du fichier
// le chenmin du fichier est relatif au script appelant cette fonction
if ($file!="" && file_exists($file)) { // si fichier est sp�cifi� et existe ....
	$fp = fopen($file, "rb");   // b c'est pour les windowsiens
	$attachment = fread($fp, filesize($file));
	fclose($fp);
	
	// puis convertir le contenu du fichier en une cha�ne de caract�re
	// certe totalement illisible mais sans caract�res exotiques
	// et avec des retours � la ligne tout les 76 caract�res
	// pour �tre conforme au format RFC 2045
	$attachment = chunk_split(base64_encode($attachment));
	
	// Ne pas oublier que chaque partie du message est s�par� par une fronti�re
	$msg .= "--$boundary\r\n";
	// Et pour chaque partie on en indique le type
	$msg .= "Content-Type: text/html; name=\"$file\"\r\n";
	// Et comment il sera cod�
	$msg .= "Content-Transfer-Encoding: base64\r\n";
	// Petit plus pour les fichiers joints
	// Il est possible de demander � ce que le fichier
	// soit si possible affich� dans le corps du mail
	$msg .= "Content-Disposition: inline; filename=\"$file\"\r\n";
	// Il est indispensable d'introduire une ligne vide entre l'ent�te et le texte
	$msg .= "\r\n";
	// C'est ici que l'on ins�re le code du fichier lu
	$msg .= $attachment . "\r\n";
	$msg .= "\r\n\r\n";
	
	// voil�, on indique la fin par une nouvelle fronti�re
	$msg .= "--$boundary--\r\n";
} 
else { // le fichier attach� n'a pas �t� trouv�
	$msg.="Le fichier $file qui devait etre attach� � ce ce message n\'a pas  �t� trouv�";
}

return mail($destinataire, $sujet, $msg,"Reply-to: $from\r\nFrom: $from\r\n".$header);
}
?>
