<? require("infos.php");
InitPage(true); // initialise en envoyant les balises de d�but <HTML> etc ...
// r�ponse a un ajout, modif ou suppression d'un enregistrement
  
// cl� de copie de la photo
if ($modif=="") { // cr�ation
$rpnuper=msq("SELECT PEX_NUPERS from PERS_EXT order by PEX_NUPERS DESC LIMIT 1");
$rp2=mysql_fetch_row($rpnuper);
$PEX_NUPERS=$rp2[0]+1;
}
$keycopy=$PEX_NUPERS."_";

$NM_TABLE="PERS_EXT";  
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
  // on ne met � jour le champ que si la variable corrr. du formulaire est d�fini�
  // ceci pour �viter les RAZ de champs masqu�s � cause des profils
  // vir� cr quand aucune case coch�e ne vide pas le champ
//  if (isset($HTTP_POST_VARS[$NOMC]) || isset($HTTP_GET_VARS[$NOMC]) || $PYAoMAJ->TypeAff=="FICFOT") {
	 if ($PYAoMAJ->TypeAff=="FICFOT") {
	    $VarFok="Fok".$NOMC;
	    $PYAoMAJ->Fok=$$VarFok;
	    $VarFname=$NOMC."_name";
	    $PYAoMAJ->Fname=$$VarFname;
	    $VarFsize=$NOMC."_size";
	    $PYAoMAJ->Fsize=$$VarFsize;
	    $VarOldFName="Old".$NOMC;
	    $PYAoMAJ->OFN=$$VarOldFName;
	    if ($modif==-1) { // suppression de l'enregistrement
	       $rqncs=msq("select ".$PYAoMAJ->NmChamp." from ".$PYAoMAJ->NmTable." where $key ");
	       $rwncs=mysql_fetch_array($rqncs);
	       $PYAoMAJ->Fname=$rwncs[0];
	       }
	    }
	 $set.=$PYAoMAJ->RetSet($keycopy); // key copy sert � la gestion des fichiers li�s
	// la gestion des fichiers est faite aussi l�-dedans
 	// } // fin si variable de formulaire d�finie
 } // fin boucle sur les champs
  
$set= substr($set,0,-2); // enl�ve la derni�re virgule et esp en trop � la fin

$key="PEX_NUPERS='$PEX_NUPERS'";
$where=" where ".$key;
$DRH_NUPERSO=$PEX_NUPERS; // d�faut, au cas ou on continue

if ($modif==1) // Si on vient d'une �dition
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

?>
<script language="javascript">
self.close();
</script>
</body>
</html>
