<?php
include_once 'includes/init.php';
print_header(); ?>

<h2>Liste des non-utilisateurs</h2>
<!-- &raquo; <a href="nonusers.php">Acc&egrave;s &agrave; l'administration</a><br><br> -->
<ul>

<?php
$res = dbi_query ('SELECT * FROM webcal_nonuser_cals');
while ( $row = dbi_fetch_row ( $res ) ) 
{       
        echo '<li><a href="week.php?user='.$row[0].'">'.$row[2].' '.$row[1].'</a></li>'."\n";
}
dbi_free_result ( $res );
?>
</ul>

<?php print_trailer(); ?>
</body>
</html>
