<?
require("infos.php");	
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...
?>
<span class="titrered20px"> 
Etat provisoire des administrateurs d'UF</span>
<table><thead>
<th>Nom</th>
<th>UF gérée</th>
<th>ID</th>
<th>Mot de passe</th>
<th>Email</th>
</thead>
<?
$NmChpId=($authType=="ldapsweb" ? "PER_LCIDLDAP" : "PER_LCIDPERS");
$rplc=msq("select * from ENV_POSSEDE,PERSONNE where POS_NUPERS=PER_NUPERS AND POS_COPROFIL='$prof'"); 
while ($rw=mysql_fetch_array($rplc)) {
	echo "<tr>";
	echo "<td>".$rw[PER_LLPRENOMPERS]." ".$rw[PER_LLNOMPERS]."</td>\n";
	echo "<td>".RecupLib("UNITE_FONCTION","UFO_NUUNITE","UFO_LLUNITE",$rw[POS_NUUNITE])."</td>\n";
	echo "<td>".$rw[$NmChpId]."</td>\n";
	echo "<td>".($authType=="ldapsweb" ? "géré par le LDAP" : $rw[POS_LMPASSWD])."</td>\n";
	echo "<td>".DispCustHT($rw[PER_MAILPERS])."</td></tr>\n";
	} // fin boucle

?>
</table>
</body>
</html>



