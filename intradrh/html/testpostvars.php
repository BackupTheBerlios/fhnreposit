<? 
require("infos.php");	
InitPage(true); // initialise en envoyant les balises de d�but <HTML> etc ...

foreach ($HTTP_POST_VARS as $NmVar=>$ValVar) {
	echo "Nom de la var: $NmVar ; valeur : $ValVar <br>\n";
		
}
