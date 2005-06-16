<?
require("infos.php");
$arg_clean=($arg_clean=="" ? 1 : $arg_clean);
sess_start($arg_clean);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>.: INTRANET DRH :.</title>
<script language="JavaScript">
function dispconfig() {
alert ('hello ducon');	
self.scrollbars.visible=false;
self.menubar.visible=false;
self.locationbar.visible=false;
self.personalbar.visible=false;
self.toolbar.visible=false;
self.statusbar.visible=false;
}
</script>
	<frameset rows="120,*" framespacing="0" border="0" frameborder="NO">
  		<frame src="bandeau.php" name="bandeau" scrolling="NO"  marginwidth="0" marginheight="1" noresize frameborder="0">
  		<frame src="<?=(isset($nav)? $nav : "accueil.php")?>" name="navigation">
</frameset>
	<noframes><!--
 	onload="javascript:dispconfig();"
 --><body>
<script language="JavaScript">
</script>		
</body>
	</noframes>	
</html>

</html>
