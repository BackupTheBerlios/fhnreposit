<html>
<?$DBHost="localhost";
$DBUser="root";
$DBPass=""; 
$NM_BASE="BOUTIQUE";
mysql_connect($DBHost,$DBUser, $DBPass) or die ("Impossible de se connecter au serveur $DBHost (user: $DBUser, passwd: $DBPass)");
?>

<!-- Date de cr�ation: 25/07/2002 -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Test Objets PYA</title>
</head>
<body>
<h2>Page de test des objets et fonctions de phpYourAdmin</h2>
<? include "fonctions.php";?>
Initialisation de l'objet : <BR>
<pre>
$tPO=new PYAobj();
// propri�t�s de base
$tPO->NmBase="BOUTIQUE"; 
$tPO->NmTable="ARTICLE";
$tPO->NmChamp="art_coedit";
// valeur du champ
$tPO->ValChp="ONF";

// appelle la m�thode d'init des autres 
$tPO->InitPO();

Le champ en question est un champ li� � un autre... 

<?  // instancie un nouvel objet
$tPO=new PYAobj();
// propri�t�s de base
$tPO->NmBase=$NM_BASE; 
$tPO->NmTable="ARTICLE";
$tPO->NmChamp="art_coedit";
// initialise les autres 
$tPO->InitPO();
// valeur du champ
$tPO->ValChp="ONF"; ?>

La liaison en question est d�finie par <?=$tPO->Valeurs;?> 

</pre>
<h3>Maintenant on passe � l'acte ...:</h3>

<BR>Libell� du champ: <?=$tPO->Libelle;?>
<pre>
Code : 
echo $tPO->Libelle;
</pre>
<BR>Valeur r�elle : <?=$tPO->ValChp;?>
<pre>
Code 
echo $tPO->ValChp;
</pre>
<BR> valeur li�e: <?=$tPO->EchoVCL();?>
<pre>
Code 
$tPO->EchoVCL();
</pre>
<BR> Edition : <?=$tPO->EchoEdit();?><br>
<pre>
Code 
$tPO->EchoEdit;
rem: le nom du controle de liste est automatiquement le nom du champ.
</pre>

</body>
</html>
