<?
	require("infos.php");	
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	</head>
	<frameset cols="<?=($frm2==true ? "165,*" : "*,1")?>" border="0" framespacing="0" frameborder="NO">
		<frame src="toc.php?nrp0=<?=$nrp0?>" name="toc" scrolling="auto" noresize>
		<frame name="contenu" src="<?=($cont!="" ? $cont : "vide.htm")?>" noresize>
	</frameset>
	<noframes>

	<body>
	<? // echo $cont."<BR>".$sig; ?>
	</body>
		

	</noframes>
</html>
