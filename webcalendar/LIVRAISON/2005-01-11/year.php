<?php

if($friendly=="1") { $popup = true; }
include_once 'includes/init.php';
send_no_cache_header ();

if (($user != $login) && $is_nonuser_admin)
  load_user_layers ($user);
else
  load_user_layers ();

if ( empty ( $year ) )
  $year = date("Y");

$thisyear = $year;
if ( $year != date ( "Y") )
  $thismonth = 1;

if ( $year > "1903" )
  $prevYear = $year - 1;
else
  $prevYear=$year;

$nextYear= $year + 1;

if ( $allow_view_other != "Y" && ! $is_admin )
  $user = "";

$boldDays = false;
if ( ! empty ( $bold_days_in_year ) && $bold_days_in_year == 'Y' ) {
  /* Pre-Load the repeated events for quckier access */
  $repeated_events = read_repeated_events (
    ( ! empty ( $user ) && strlen ( $user ) ) ? $user : $login, $cat_id, $year . "0101" );

  /* Pre-load the non-repeating events for quicker access */
  $events = read_events ( ( ! empty ( $user ) && strlen ( $user ) )
    ? $user : $login, $year . "0101", $year . "1231", $cat_id );
  $boldDays = true;
}

// Include unapproved events?
$get_unapproved = ( $DISPLAY_UNAPPROVED == 'Y' );
if ( $user == "__public__" )
  $get_unapproved = false;

 print_header();
 ?>
 
<div class="title">
	<a title="<?php etranslate("Previous")?>" class="prev" href="year.php?year=<?php echo $prevYear; if ( ! empty ( $user ) ) echo "&amp;user=$user";?>"><img src="leftarrow.gif" alt="<?php etranslate("Previous")?>" /></a>
	<a title="<?php etranslate("Next")?>" class="next" href="year.php?year=<?php echo $nextYear; if ( ! empty ( $user ) ) echo "&amp;user=$user";?>"><img src="rightarrow.gif" alt="<?php etranslate("Next")?>" /></a>
	<span class="date"><?php echo $thisyear ?></span>
	<span class="user"><?php
		if ( $single_user == "N" ) {
			echo "<br />\n";
			if ( ! empty ( $user ) ) {
				user_load_variables ( $user, "user_" );
				echo $user_fullname;
			} else {
				echo $fullname;
			}
			if ( $is_assistant )
				echo "<br /><span style=\"font-weight:bold;\">-- " . translate("Assistant mode") . " --</span>";
		}
	?></span>
</div>
<br />
 
<div align="center">
	<table class="main">
		<tr><td>
			<?php display_small_month(1,$year,False); ?></td><td>
			<?php display_small_month(2,$year,False); ?></td><td>
			<?php display_small_month(3,$year,False); ?></td><td>
			<?php display_small_month(4,$year,False); ?>
		</td></tr>
		<tr><td>
			<?php display_small_month(5,$year,False); ?></td><td>
			<?php display_small_month(6,$year,False); ?></td><td>
			<?php display_small_month(7,$year,False); ?></td><td>
			<?php display_small_month(8,$year,False); ?>
		</td></tr>
		<tr><td>
			<?php display_small_month(9,$year,False); ?></td><td>
			<?php display_small_month(10,$year,False); ?></td><td>
			<?php display_small_month(11,$year,False); ?></td><td>
			<?php display_small_month(12,$year,False); ?>
		</td></tr>
	</table>
</div>

<br />
<?php display_unapproved_events ( $login ); ?>
<br />
<a title="<?php etranslate("Generate printer-friendly version")?>" class="printer" href="#" onClick="popup('year.php?<?php
  if ( $thisyear )
    echo "year=$thisyear&amp;";
  if ( $user != $login && ! empty ( $user ) )
    echo "user=$user&amp;";?>friendly=1', '850', '650');"><img src="imprimante.gif" alt="imprimer" border="0"></a>

<?php

if($friendly == "1")
{
	echo "<center><a href=\"#\" onClick=\"window.print();\"><img src=\"imprimante.gif\" border=\"0\" alt=\"Imprimer\"> <a href=\"#\" onClick=\"window.close();\"><img src=\"bouton_fermer.gif\" border=\"0\" alt=\"Fermer\"></center>\n";
}

print_trailer(); ?>
</body>
</html>
