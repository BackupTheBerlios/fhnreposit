<?
	require("infos.php");
	// se sert de la bricole pour passer le tableau
	if (isset($rq_DRH_NUUNITE))$sp_NUUNITEG=implode(":",$rq_DRH_NUUNITE);

	$args="tf_PER_LMTYPERS=$tf_PER_LMTYPERS&rq_PER_LMTYPERS=$rq_PER_LMTYPERS&lc_FirstPers=$lc_FirstPers&tf_DRH_LLACTIVITE=$tf_DRH_LLACTIVITE&rq_DRH_LLACTIVITE=$rq_DRH_LLACTIVITE&sp_DRH_LLACTIVITE=$sp_DRH_LLACTIVITE&tf_PER_LLNOMPERS=$tf_PER_LLNOMPERS&rq_PER_LLNOMPERS=$rq_PER_LLNOMPERS&tf_PER_LLPRENOMPERS=$tf_PER_LLPRENOMPERS&rq_PER_LLPRENOMPERS=$rq_PER_LLPRENOMPERS&tf_DRH_NUUNITE=LDM&sp_NUUNITEG=$sp_NUUNITEG&cfrf=true";
?>
<html>
 	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	</head>
	<frameset cols="165,*" border="0" framespacing="0" frameborder="NO">
		<frame src="toc.php?nrp0=4" name="toc" scrolling="auto" noresize>
		<frame name="contenu" src="list_pers.php?<?=$args?>" noresize>
	</frameset>
	<noframes>

 	<body>
	<? echo "args=$args" ?>
	</body>
	</noframes>
</html>
