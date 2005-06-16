<?
require("infos.php");	
InitPage(true,"Informations de version"); // initialise en envoyant les balises de début <HTML> etc ...
?>

<div align="center">
<a name="haut"></a>
<table width="490" border="0">
<tr><td align="center" colspan="2" width="450">
<? EchoTitIm1("Informations de version");?>
</td></tr>
<tr><td colspan="2"><br><br>
<div align="center"><span class="chapitrered12px">Intranet DRH version <?=$VerNoDRH?></span>
</div><br><br>
<b>version 0.140 du 10/03/03</b><br>
&#149; gestion des externes et société externes<br>
&#149; correction bug signalé par Thierry Delssalles (guillemets dans les champs les tyronquaient à cause du HTML)<br><br>
<b>version 0.140 du 05/02/03</b><br>
&#149; plus de % dans les boites de recherche<br>
&#149; optimisation de la taille des popup, et gestion correcte de leur ouverture, fermeture (boutons fermer ajoutés dans ce contexte)<br>
&#149; moulinette sur les téléphones effectués, et rajout d'une mention en édition (script de correction auto à venir)<br>
&#149; gestion des sépérateurs de fonctions &#149; spécialités etc ..<br>
&#149; Moulinette de mise en Majuscule de la table paramètres CORPS
sur fiche UF<br>
&#149; calcul auto dynamique des effectifs des UF (!)<br>
&#149; plus d'affichage de la pos hierarchique des UF en consult<br>
sur fiche personne
&#149; n'affiche plus la mention liste rouge<br>
&#149; affiche le correspondant que si non vide<br>
&#149; résolution du pb si on appuie sur ENtrée sur la page d'accueil au lieu de cliquer sur le bouton (popup tjrs sans barre d'adresse)<br><br>
<b>version 0.135 du 12/12/02</b><br>
&#149; affichage des personnes dont la position corr. a le champ TAC_AFFANN='O' (avant, on avait que les actifs)<br>
&#149; en édition/consultation DRH ou ADM UF, les positions tq TAC_APPEPA='O' sont préselectionnées par défaut (fait en JS)<br>
&#149; blocage des éditions UF comme prévu au départ (reblocage du déblocage temporaire)<br>
<b>version 0.130 du 12/12/02</b><br>
&#149; Intégration nouveau visuel page accueil, sur site de test uniquement<br>
<b>version 0.125 du 6/12/02</b><br>
&#149; Mise à jour des menus suivant specs<br>
&#149; Maquette fonctionnelle de page d'accueil<br>
<b>version 0.120 du 27/11/02</b><br>
&#149; Etat liste complète des fiches détaillées pour les ADM d'UF<br>
<b>version 0.115 du 22/11/02</b><br>
&#149; Autorisation modif temporaire du titre par les AdmUF<br>
<b>version 0.11 du 20/11/02</b><br>
&#149; Utilisation méthode de mise à jour des champ de PYA<br>
&#149; Gestion des MAJ des coordonnées des U.F.<br>
<b>version 0.10 du 19/11/02</b><br>
&#149; deplacement des fichiers php ds un dossier html, pour pb de compatiblité de liens relatifs avec PYA<br>
&#149; correction de bugs qui effaçaient le nom en édition par un ADM d'UF, et ne mettait pas à jour ttes les infos de mail ID, export etc .. <br>
&#149; rajout d'un bouton imprimer de la fiche<br>
<br><br>
<a href="javascript:this.close();"><img src="../../intranet/partage/IMAGES/bout_fermer.gif" border="0" width="70" height="11" alt="Fermer cette fenêtre"></a>
 
</td></tr></table>
</div>
</BODY>
</HTML>

