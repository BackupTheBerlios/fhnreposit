<? require("infos.php");
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...
// réponse a un ajout, modif ou suppression d'un enregistrement
  
// clé de copie du logo et du plan d'accès
/*
Structure de cette table
GEO_POSIT (Positions géographiques Liste des entités ou se trouvent du personnel HN)
GEO_NUPOSIT, de type int(11) Type aff.: HID
GEO_LLPOSIT, de type varchar(50) - Libellé Type aff.: AUT
GEO_TELPOSIT, de type varchar(20) - Téléphone Type aff.: AUT
GEO_FAXPOSIT, de type varchar(20) - Fax Type aff.: AUT
GEO_PORPOSIT, de type varchar(20) - Tel Mobile Type aff.: AUT
GEO_LCABREGE, de type varchar(4) - N° Tel abrégé Type aff.: AUT
GEO_MAILUNITE, de type varchar(100) - Email Type aff.: AUT
GEO_LLADRES, de type varchar(40) - Adresse Type aff.: AUT
GEO_LLADRES2, de type varchar(40) - Complément d\'adresse Type aff.: AUT
GEO_COPOSTAL, de type varchar(20) - Code postal Type aff.: AUT
GEO_LLCOMMU, de type varchar(40) - Ville Type aff.: AUT
GEO_COPAYS, de type varchar(20) - Pays Type aff.: AUT
GEO_NBEFFEC, de type smallint(6) - Effectif Type aff.: AUT
GEO_COUFORAT, de type varchar(20) - Unité fonctionnelle de rattachement Type aff.: LDL Valeurs: UNITE_FONCTION,UFO_NUUNITE,UFO_LLUNITE
GEO_FICHASS, de type varchar(255) - Fichier associé Type aff.: FICFOT Valeurs: ../drh_photo_GEO/
GEO_PLANACC, de type varchar(255) - Plan d\'accès Type aff.: FICFOT Valeurs: ../drh_planacc_GEO/
GEO_POSX, de type float - Position X Type aff.: AUT
GEO_POSY, de type float - Position Y Type aff.: AUT
GEO_DTCREA, de type date - Date de création Type aff.: STA
GEO_DTMAJ, de type date - Date de mise à jour Type aff.: STA
GEO_COOPE, de type smallint(6) - Personne ayant effectué la MAJ Type aff.: AUT Valeurs: PERSONNE,PER_NUPERS,PER_LMTITREPER, !PER_LLPRENOMPERS, !@PER_LLNOMPERS
*/
$keycopy=GEO_NUPOSIT."_";

$NM_TABLE="GEO_POSIT";  
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
  /*
  $PYAoMAJ->ValChp=($_FILES[$NOMC][tmp_name]!="" ? $_FILES[$NOMC][tmp_name] : $PYAoMAJ->ValChp);
     $VarOldFName="Old".$NOMC;
     $PYAoMAJ->OFN=$$VarOldFName;
  */
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

  } // fin boucle sur les champs
  
$set= substr($set,0,-2); // enlève la dernière virgule et esp en trop à la fin

$key="GEO_NUPOSIT='$GEO_NUPOSIT'";
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

msq($strqaj);

echo "requete sql ajout/modif infos Unité position géographique <BR> $strqaj <BR><BR>";

?>
<script language="javascript">
window.opener.location.reload();
self.close();
</script>
</body>
</html>
