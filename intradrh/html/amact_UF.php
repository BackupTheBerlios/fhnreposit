<? require("infos.php");
InitPage(true); // initialise en envoyant les balises de d�but <HTML> etc ...
// r�ponse a un ajout, modif ou suppression d'un enregistrement
  
// cl� de copie du logo et du plan d'acc�s

$keycopy=$UFO_NUUNITE."_";

$NM_TABLE="UNITE_FONCTION";  
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
  if ($PYAoMAJ->TypeAff=="FICFOT") {
     $VarFok="Fok".$NOMC;
     $VarFname=$NOMC."_name"; // ancienne m�thode
     $PYAoMAJ->Fname=($$VarFname !="" ? $$VarFname : $_FILES[$NOMC][name]);
     $VarFsize=$NOMC."_size";// ancienne m�thode
     $PYAoMAJ->Fsize=($$VarFsize!="" ? $$VarFsize : $_FILES[$NOMC][size]);
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

  } // fin boucle sur les champs
  
$set= substr($set,0,-2); // enl�ve la derni�re virgule et esp en trop � la fin

$key="UFO_NUUNITE='$UFO_NUUNITE'";
$where=" where ".$key;

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

echo "requete sql ajout/modif infos Unit� fonctionnelle <BR> $strqaj <BR><BR>";

?>
<script language="javascript">
window.opener.location.reload();
self.close();
</script>
</body>
</html>
