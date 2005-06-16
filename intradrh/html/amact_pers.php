<? require("infos.php");
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...
// réponse a un ajout, modif ou suppression d'un enregistrement
  
// clé de copie de la photo
if ($modif=="") { // création
$rpnuper=msq("SELECT PER_NUPERS from PERSONNE order by PER_NUPERS DESC LIMIT 1");
$rp2=mysql_fetch_row($rpnuper);
$PER_NUPERS=$rp2[0]+1;
}
$keycopy=$PER_NUPERS."_";

$NM_TABLE="PERSONNE";  
// construction du set
$rq1=msq("SELECT NM_CHAMP from $TBDRHname where NM_TABLE='$NM_TABLE' AND NM_CHAMP!='$NmChDRHT' ORDER BY ORDAFF, LIBELLE");

$PYAoMAJ=new PYAobj();

$PYAoMAJ->NmBase=$DBDRHName;
$PYAoMAJ->NmTable=$NM_TABLE;
$PYAoMAJ->TypEdit=$modif;

while ($res1=mysql_fetch_array($rq1))
  {
  $NOMC=$res1[NM_CHAMP]; // nom variable=nom du champ
  $PYAoMAJ->NmChamp=$NOMC;
  $PYAoMAJ->InitPO();
  $PYAoMAJ->ValChp=$$NOMC; // issu du formulaire
  // on ne met à jour le champ que si la variable corrr. du formulaire est définié
  // ceci pour éviter les RAZ de champs masqués à cause des profils
//  if (isset($HTTP_POST_VARS[$NOMC]) || isset($HTTP_GET_VARS[$NOMC]) || $PYAoMAJ->TypeAff=="FICFOT") {
	  if ($PYAoMAJ->TypeAff=="FICFOT") {
	     	$VarFok="Fok".$NOMC;
	     	$PYAoMAJ->Fok=$$VarFok;
		$VarFname=$NOMC."_name"; // ancienne méthode
		$PYAoMAJ->Fname=($$VarFname !="" ? $$VarFname : $_FILES[$NOMC][name]);
		$VarFsize=$NOMC."_size";// ancienne méthode
		$PYAoMAJ->Fsize=($$VarFsize!="" ? $$VarFsize : $_FILES[$NOMC][size]);
	     	$VarOldFName="Old".$NOMC;
	     	$PYAoMAJ->OFN=$$VarOldFName;
	     	if ($modif==-1) { // suppression de l'enregistrement
	        	$rqncs=msq("select ".$PYAoMAJ->NmChamp." from ".$PYAoMAJ->NmTable." where $key ");
	        	$rwncs=mysql_fetch_array($rqncs);
	        	$PYAoMAJ->Fname=$rwncs[0];
	        }
	  }
	  $set.=$PYAoMAJ->RetSet($keycopy); // key copy sert à la gestion des fichiers liés
	  // la gestion des fichiers est faite aussi là-dedans
	  //} // fin si variable de formulaire existe  	
 } // fin boucle sur les champs
  
$set= substr($set,0,-2); // enlève la dernière virgule et esp en trop à la fin

$key="PER_NUPERS='$PER_NUPERS'";
$where=" where ".$key;
$DRH_NUPERSO=$PER_NUPERS; // défaut, au cas ou on continue

if ($modif==1) // Si on vient d'une édition
  {
  $strqaj="UPDATE $NM_TABLE SET $set $where";
  }
else if ($modif==-1) // // Si on vient d'une suppression
  {
  $strqaj="DELETE FROM $NM_TABLE $where";

  }
else // Si on vient de nv enregistrement
  {
  // Ajout dans la table Mysql
  $strqaj="INSERT INTO $NM_TABLE SET $set";
  }

msq($strqaj);
if ($debug) echo "requete sql ajout/modif infos personne: <BR> $strqaj <BR><BR>";

// recupère le n° de la personne en nouveauté pour la DRH

if ($modif=="") $DRH_NUPERSO=mysql_insert_id(); // au cas ou on continue


// modif des infos DRH : seul root et l'adm DRH on le droit d'y toucher
if ($ss_InfoUser[COPROFIL]=="SADMIN" || $ss_InfoUser[COPROFIL]=="DRH_ADM" || $ss_InfoUser[COPROFIL]=="UF_ADM") {
// viré car le n° SDL7 n'est plus un index de type unique
//	if ($DRH_NUSDL7=="" || $DRH_NUSDL7==0 ) $DRH_NUSDL7=$DRH_NUPERSO;
	$NM_TABLE="INFOS_DRH";  
	// construction du set
	$set=""; // reset set

	$rq1=msq("SELECT NM_CHAMP from $TBDRHname where NM_TABLE='$NM_TABLE' AND NM_CHAMP!='$NmChDRHT' ORDER BY ORDAFF, LIBELLE");

	$PYAoMAJ->NmTable=$NM_TABLE;

	while ($res1=mysql_fetch_array($rq1))
	  {
	  $NOMC=$res1[NM_CHAMP]; // nom variable=nom du champ
	  $PYAoMAJ->NmChamp=$NOMC;
	  $PYAoMAJ->InitPO();
	  $PYAoMAJ->ValChp=$$NOMC; // issu du formulaire
	  if ($NOMC=="DRH_COSPECIAL") echovar($NOMC); 
      // on ne met à jour le champ que si la variable corrr. du formulaire est définie
	  // en création sinon la clé ne passe pas (bug)
  	  // ceci pour éviter les RAZ de champs masqués à cause des profils
	  //if (isset($HTTP_POST_VARS[$NOMC]) || isset($HTTP_GET_VARS[$NOMC]) || $PYAoMAJ->TypeAff=="FICFOT" || $modif==""){
	
		  // ne sert à rien car pas de fichiers liés, mis on le laisse on ne sait jamais
		  if ($PYAoMAJ->TypeAff=="FICFOT") {
		     $VarFok="Fok".$NOMC;
		     $PYAoMAJ->Fok=$$VarFok;
		     $VarFname=$NOMC."_name";
		     $PYAoMAJ->Fname=$$VarFname;
		     $VarFsize=$NOMC."_size";
		     $PYAoMAJ->Fsize=$$VarFsize;
		     $VarOldFName="Old".$NOMC;
		     $PYAoMAJ->OFN=$$VarOldFName;
		     if ($modif==-1) { // si suppression de l'enregistrement
		        $rqncs=msq("select ".$PYAoMAJ->NmChamp." from ".$PYAoMAJ->NmTable." where $key ");
		        $rwncs=mysql_fetch_array($rqncs);
		        $PYAoMAJ->Fname=$rwncs[0];
		        }
		     }
		  $set.=$PYAoMAJ->RetSet($keycopy); // key copy sert à la gestion des fichiers liés
		  // la gestion des fichiers est faite aussi là-dedans
		  //} // fin si variable de formulaire existe  	
		} // fin boucle sur les champs

	$set= substr($set,0,-2); // enlève la dernière virgule et esp en trop à la fin
	
	$key="DRH_NUPERSO='$DRH_NUPERSO'";

	$where=" where ".$key;
	if ($modif==1) // Si on vient d'une édition
	  {
	  $strqaj="UPDATE $NM_TABLE SET $set $where";
	  }
	else if ($modif==-1) // // Si on vient d'une suppression
	  {
	  $strqaj="DELETE FROM $NM_TABLE $where";
	
	  }
	else // Si on vient de nv enregistrement
	  {
	  // Ajout dans la table Mysql
	  $strqaj="INSERT INTO $NM_TABLE SET $set";
	  }
	
	echo "Val: $res1[DRH_COSPECIAL] adds:".addslashes($res1[DRH_COSPECIAL]);
	echo "requete sql ajout/modif infos DRH :<BR> $strqaj";

	msq($strqaj); 

} // fin modif des infos DRH OK

?>
<script language="javascript">
window.opener.location.reload();
self.close();
</script>
</body>
</html>
