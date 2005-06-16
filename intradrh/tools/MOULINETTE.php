<?
require("../html/infos.php");	
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...

// Moulinette qui rajoute des , en début et fin de champs pseudo set
$TABLE="CORPS";
$CHP_SET="COR_LLCORPS";
$CHP_CLE="COR_COCORPS";

$rpmoul=msq("select * from $TABLE");
while ($rw=mysql_fetch_array($rpmoul)) {
		$rw[$CHP_SET]=addslashes(ucfirst($rw[$CHP_SET]));
		msq("update $TABLE set $CHP_SET='$rw[$CHP_SET]' where $CHP_CLE='$rw[$CHP_CLE]'"); 
		echo $rw[$CHP_SET]."<BR>";
	}

?>
</body>
</html>



