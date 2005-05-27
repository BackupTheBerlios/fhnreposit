<?php

if($friendly=="1") { $popup=true; }
include_once 'includes/init.php';

if (($user != $login) && $is_nonuser_admin)
  load_user_layers ($user);
else
  load_user_layers ();

load_user_categories ();

$wday = strftime ( "%w", mktime ( 3, 0, 0, $thismonth, $thisday, $thisyear ) );

$now = mktime ( 3, 0, 0, $thismonth, $thisday, $thisyear );
$nowYmd = date ( "Ymd", $now );

$next = mktime ( 3, 0, 0, $thismonth, $thisday + 1, $thisyear );
$nextYmd = date ( "Ymd", $next );
$nextyear = date ( "Y", $next );
$nextmonth = date ( "m", $next );
$nextday = date ( "d", $next );

$prev = mktime ( 3, 0, 0, $thismonth, $thisday - 1, $thisyear );
$prevYmd = date ( "Ymd", $prev );
$prevyear = date ( "Y", $prev );
$prevmonth = date ( "m", $prev );
$prevday = date ( "d", $prev );

if ( ! empty ( $bold_days_in_year ) && $bold_days_in_year == 'Y' ) {
	$boldDays = true;
} else {
	$boldDays = false;
}

$startdate = sprintf ( "%04d%02d01", $thisyear, $thismonth );
$enddate = sprintf ( "%04d%02d31", $thisyear, $thismonth );

$HeadX = '';
if ( $auto_refresh == "Y" && ! empty ( $auto_refresh_time ) ) {
  $refresh = $auto_refresh_time * 60; // convert to seconds
  $HeadX = "<meta http-equiv=\"refresh\" content=\"$refresh; url=day.php?$u_url" .
    "date=$nowYmd$caturl\" />\n";
}
$INC = array('js/popups.php');
print_header($INC,$HeadX);
?>

<?php
/* Pre-Load the repeated events for quckier access */
$repeated_events = read_repeated_events ( empty ( $user ) ? $login : $user,
  $cat_id, $startdate  );

/* Pre-load the non-repeating events for quicker access */
$events = read_events ( empty ( $user ) ? $login : $user, $startdate, $enddate,
  $cat_id  );
?>

<table>
<tr><td style="vertical-align:top; width:82%;">
<div style="border-width:0px;">
<a title="<?php etranslate("Next"); ?>" class="next" href="day.php?<?php echo $u_url;?>date=<?php echo $nextYmd . $caturl;?>"><img src="rightarrow.gif" alt="<?php etranslate("Next"); ?>" /></a>
<a title="<?php etranslate("Previous"); ?>" class="prev" href="day.php?<?php echo $u_url;?>date=<?php echo $prevYmd . $caturl;?>"><img src="leftarrow.gif" alt="<?php etranslate("Previous"); ?>" /></a>
<div class="title">
<span class="date"><?php
  echo date_to_str ( $nowYmd );
?></span>
<span class="user"><?php
  // display current calendar's user (if not in single user)
  if ( $single_user == "N" ) {
    echo "<br />";
    echo $user_fullname;
  }
  if ( $is_nonuser_admin )
    echo "<br />-- " . translate("Admin mode") . " --";
  if ( $is_assistant )
    echo "<br />-- " . translate("Assistant mode") . " --";
?></span>

</div>
</div>


<table cellspacing="0" cellpadding="0" width="100%" style="border:0px;background-color:transparent;">
<tr><td style="border:0px;background-color:transparent;">
<div id="trailer">
<?php
  if ( $categories_enabled == "Y" ) {
    echo "<br />\n<br />\n";
    print_category_menu( 'day', sprintf ( "%04d%02d%02d",$thisyear, $thismonth, $thisday ), $cat_id );
  }
?>
<form action="month.php" method="get" name="SelectMonth" id="monthform">
<?php
  if ( ! empty ( $user ) && $user != $login )
    echo "<input type=\"hidden\" name=\"user\" value=\"$user\" />\n";
  if ( ! empty ( $cat_id ) && $categories_enabled == "Y"
    && ( ! $user || $user == $login ) )
    echo "<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\" />\n";
?>
<label for="monthselect"><?php etranslate("Month")?>:&nbsp;</label>
<select name="date" id="monthselect">
<?php
  if ( ! empty ( $thisyear ) && ! empty ( $thismonth ) ) {
    $m = $thismonth;
    $y = $thisyear;
  } else {
    $m = date ( "m" );
    $y = date ( "Y" );
  }
  $d_time = mktime ( 3, 0, 0, $m, 1, $y );
  $thisdate = date ( "Ymd", $d_time );
  $y--;
  for ( $i = 0; $i < 25; $i++ ) {
    $m++;
    if ( $m > 12 ) {
      $m = 1;
      $y++;
    }
    $d = mktime ( 3, 0, 0, $m, 1, $y );
    echo "<option value=\"" . date ( "Ymd", $d ) . "\"";
    if ( date ( "Ymd", $d ) == $thisdate )
      echo " selected=\"selected\"";
    echo ">";
    echo date_to_str ( date ( "Ymd", $d ), $DATE_FORMAT_MY, false, true );
    echo "</option>\n";
  }
?>
</select>
<input type="submit" value="<?php etranslate('Go to'); ?>">
</form>

<form action="week.php" method="get" name="SelectWeek" id="weekform">
<?php
  if ( ! empty ( $user ) && $user != $login )
    echo "<input type=\"hidden\" name=\"user\" value=\"$user\" />\n";
  if ( ! empty ( $cat_id ) && $categories_enabled == "Y"
    && ( ! $user || $user == $login ) )
    echo "<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\" />\n";
?>
<label for="weekselect"><?php etranslate("Week")?>:&nbsp;</label>
<select name="date" id="weekselect">
<?php
  if ( ! empty ( $thisyear ) && ! empty ( $thismonth ) ) {
    $m = $thismonth;
    $y = $thisyear;
  } else {
    $m = date ( "m" );
    $y = date ( "Y" );
  }
  if ( ! empty ( $thisday ) ) {
    $d = $thisday;
  } else {
    $d = date ( "d" );
  }
  $d_time = mktime ( 3, 0, 0, $m, $d, $y );
  $thisdate = date ( "Ymd", $d_time );
  $wday = date ( "w", $d_time );
  if ( $WEEK_START == 1 )
    $wkstart = mktime ( 3, 0, 0, $m, $d - ( $wday - 1 ), $y );
  else
    $wkstart = mktime ( 3, 0, 0, $m, $d - $wday, $y );
  for ( $i = -7; $i <= 7; $i++ ) {
    $twkstart = $wkstart + ( 3600 * 24 * 7 * $i );
    $twkend = $twkstart + ( 3600 * 24 * 6 );
    echo "<option value=\"" . date ( "Ymd", $twkstart ) . "\"";
    if ( date ( "Ymd", $twkstart ) <= $thisdate &&
      date ( "Ymd", $twkend ) >= $thisdate )
      echo " selected=\"selected\"";
    echo ">";
    printf ( "%s - %s",
      date_to_str ( date ( "Ymd", $twkstart ), $DATE_FORMAT_MD, false, true ),
      date_to_str ( date ( "Ymd", $twkend ), $DATE_FORMAT_MD, false, true ) );
    echo "</option>\n";
  }
?>
</select>
<input type="submit" value="<?php etranslate('Go to'); ?>">
</form>

<form action="year.php" method="get" name="SelectYear" id="yearform">
<?php
  if ( ! empty ( $user ) && $user != $login )
    echo "<input type=\"hidden\" name=\"user\" value=\"$user\" />\n";
  if ( ! empty ( $cat_id ) && $categories_enabled == "Y"
    && ( ! $user || $user == $login ) )
    echo "<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\" />\n";
?>
<label for="yearselect"><?php etranslate("Year")?>:&nbsp;</label>
<select name="year" id="yearselect">
<?php
  if ( ! empty ( $thisyear ) ) {
    $y = $thisyear;
  } else {
    $y = date ( "Y" );
  }
  for ( $i = $y - 2; $i < $y + 6; $i++ ) {
    echo "<option value=\"$i\"";
    if ( $i == $y )
      echo " selected=\"selected\"";
    echo ">$i</option>\n";
  }
?>
</select>
<input type="submit" value="<?php etranslate('Go to'); ?>">
</form>
</div>
</tr></td></table>

<table class="glance" cellspacing="0" cellpadding="0">
<?php
if ( empty ( $TIME_SLOTS ) )
  $TIME_SLOTS = 24;

print_day_at_a_glance ( date ( "Ymd", $now ),
  empty ( $user ) ? $login : $user, $can_add );
?>
</table>
</td>
<td style="vertical-align:top;">
<!-- START MINICAL -->
<div class="minicalcontainer">
<?php display_small_month ( $thismonth, $thisyear, true ); ?>
</div>
</td></tr></table>
<br />
<?php
	echo $eventinfo;

  display_unapproved_events ( ( $is_assistant || $is_nonuser_admin ? $user : $login ) );
?>
<br />
<a title="<?php etranslate("Generate printer-friendly version")?>" class="printer" href="#" onClick="popup('day.php?<?php
  echo $u_url;
  if ( $thisyear ) {
    echo "year=$thisyear&amp;month=$thismonth&amp;day=$thisday&amp;";
  }
  if ( ! empty ( $cat_id ) ) echo "cat_id=$cat_id&amp;";?>friendly=1', '800', '600');"><img src="imprimante.gif" alt="imprimer" border="0"></a>

<?php 

if($friendly == "1")
{
	echo "<center><a href=\"#\" onClick=\"window.print();\"><img src=\"imprimante.gif\" border=\"0\" alt=\"Imprimer\"> <a href=\"#\" onClick=\"window.close();\"><img src=\"bouton_fermer.gif\" border=\"0\" alt=\"Fermer\"></center>\n";
}

print_trailer (); ?>
</body>
</html>
