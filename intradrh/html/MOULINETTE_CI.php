<?
require("infos.php");	
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...


$CSP="IDE";

$rpmoul=msq("select * from CI where CI_COUF!=''");
while ($rw=mysql_fetch_array($rpmoul)) {
	$TOPMAIL="N";
	if ($rw[CI_MAIL]!="") {
		$rw[CI_MAIL].="@haras-nationaux.fr";
		$TOPMAIL="O";}
	$UPD="update PERSONNE set PER_LCIDPERS='$rw[CI_CODE]',PER_MAILPERS='$rw[CI_MAIL]',PER_TOPMAIL='$TOPMAIL' where PER_NUPERS=$rw[CI_NUPERSO]";
	echo $UPD."<br>\n";
	msq ($UPD);
	$ISP="insert into ENV_POSSEDE set POS_NUPERS=$rw[CI_NUPERSO], POS_COPROFIL='UF_ADM', POS_LMPASSWD='$rw[CI_PASS]',POS_NUUNITE='$rw[CI_COUF]'";
//	if (!RecupLib("ENV_POSSEDE","POS_NUPERS","POS_COPROFIL",$rw[CI_NUPERSO]))
		echo ($ISP)."<br>\n";
		msq ($ISP);
	} // fin boucle

?>
</body>
</html>



