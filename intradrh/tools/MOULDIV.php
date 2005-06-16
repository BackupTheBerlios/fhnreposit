<?
require("infos.php");	
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...

// Moulinette qui rajoute des , en début et fin de champs pseudo set
$TABLE="UNITE_FONCTION";
$CHP_SET="UFO_LLUNITE";
$CHP_CLE="UFO_NUUNITE";

$rpmoul=msq("select * from $TABLE");
while ($rw=mysql_fetch_array($rpmoul)) {
	if (strstr($rw[$CHP_SET],"Haras d")) {
		$rw[$CHP_SET]=str_replace( "Haras d", "Haras national d", $rw[$CHP_SET]);
		$rw[$CHP_SET]=addslashes($rw[$CHP_SET]);
		$reqU="update $TABLE set $CHP_SET='$rw[$CHP_SET]' where $CHP_CLE='$rw[$CHP_CLE]'";
		echo $reqU."<BR>\n"; 
		msq($reqU); 
	}
}
?>
</body>
</html>



