<? require("infos.php");
session_start();
if (!$debug) $noinf="&lc_parenv[noinfos]=true";
$pagePYA="req_table.php";
$GUSMAJ="lc_CO_USMAJ=".$ss_InfoUser[NUPERS];
$GTABLE="lc_NM_TABLE=".$PYATABLE;
$GBASE="lc_DBName=$DBDRHName";
$GADRR="lc_adrr[$pagePYA]=0";
//$clean="&lc_clean=1";
$UrlRedir=$ChemPYA.$pagePYA."?$GUSMAJ&$GTABLE&$GBASE&$GADRR$noinf$clean";
?>
<html>
<!-- Date de création: 26/02/2003 -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title></title>
<script language="JavaScript">
window.location.href=('<?=$UrlRedir?>');
</script>
</head>
<body>
Si la redirection automatique ne fonctionne pas, cliquez
<a href="<?=$UrlRedir?>">ICI</a>
</body>
</html>

