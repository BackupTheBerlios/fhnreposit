<? require("infos.php");
InitPage(true); // initialise en envoyant les balises de début <HTML> etc ...
// modification des infos de mail
$PER_LCIDPERS=addslashes($PER_LCIDPERS);
msq("UPDATE PERSONNE SET PER_TOPMAIL='$PER_TOPMAIL', PER_MAILPERS='$PER_MAILPERS', PER_LCIDPERS='$PER_LCIDPERS', PER_EXPEXT='$PER_EXPEXT' where PER_NUPERS='$PER_NUPERS'");

?>
<script language="javascript">
self.close();
</script>
</body>
</html>
