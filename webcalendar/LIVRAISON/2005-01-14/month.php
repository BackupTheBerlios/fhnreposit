<?php

if($friendly=="1") { $popup=true; }
include_once 'includes/init.php';

if (($user != $login) && $is_nonuser_admin)
  load_user_layers ($user);
else
  load_user_layers ();

load_user_categories ();

$next = mktime ( 3, 0, 0, $thismonth + 1, 1, $thisyear );
$nextyear = date ( "Y", $next );
$nextmonth = date ( "m", $next );
//$nextdate = date ( "Ymd" );

$prev = mktime ( 3, 0, 0, $thismonth - 1, 1, $thisyear );
$prevyear = date ( "Y", $prev );
$prevmonth = date ( "m", $prev );
//$prevdate = date ( "Ymd" );

if ( ! empty ( $bold_days_in_year ) && $bold_days_in_year == 'Y' ) {
	$boldDays = true;
	$startdate = sprintf ( "%04d%02d01", $prevyear, $prevmonth );
	$enddate = sprintf ( "%04d%02d31", $nextyear, $nextmonth );
} else {
	$boldDays = false;
	$startdate = sprintf ( "%04d%02d01", $thisyear, $thismonth );
	$enddate = sprintf ( "%04d%02d31", $thisyear, $thismonth );
}

$HeadX = '';
if ( $auto_refresh == "Y" && ! empty ( $auto_refresh_time ) ) {
  $refresh = $auto_refresh_time * 60; // convert to seconds
  $HeadX = "<meta http-equiv=\"refresh\" content=\"$refresh; url=month.php?$u_url" .
    "year=$thisyear&amp;month=$thismonth$caturl\" />\n";
}
$INC = array('js/popups.php');
print_header($INC,$HeadX);

/* Pre-Load the repeated events for quckier access */
$repeated_events = read_repeated_events (
  ( ! empty ( $user ) && strlen ( $user ) ) ? $user : $login, $cat_id, $startdate );

/* Pre-load the non-repeating events for quicker access */
$events = read_events ( ( ! empty ( $user ) && strlen ( $user ) )
  ? $user : $login, $startdate, $enddate, $cat_id );
if(!$popup) echo '<br />'; 
display_small_month ( $prevmonth, $prevyear, true, true, "prevmonth" );
display_small_month ( $nextmonth, $nextyear, true, true, "nextmonth" );

if(!$popup) echo '<br /><br /><br /><br />'; 
?>
<div class="title">
<span class="date"><br /><?php
  echo date_to_str ( sprintf ( "%04d%02d01", $thisyear, $thismonth ),
    $DATE_FORMAT_MY, false, false );
?></span>
<span class="user"><?php
  if ( $single_user == "N" ) {
    echo "<br />\n";
    echo $user_fullname;
  }
  if ( $is_nonuser_admin )
    echo "<br />-- " . translate("Admin mode") . " --";
  if ( $is_assistant )
    echo "<br />-- " . translate("Assistant mode") . " --";
?></span>
<div>&nbsp;</div>
</div>
<br /><br />

<table cellspacing="0" cellpadding="0" width="100%" style="border:0px;background-color:transparent;">
<tr><td style="border:0px;background-color:transparent;">
<div id="trailer">
<?php
  if ( $categories_enabled == "Y" && (!$user || ($user == $login || $is_assistant ))) {
    echo "<br /><br />\n";
    print_category_menu('month',sprintf ( "%04d%02d01",$thisyear, $thismonth ),$cat_id );
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


<table class="main" style="clear:both;" cellspacing="0" cellpadding="0">
<tr>
	<?php if ( $WEEK_START == 0 ) { ?>
		<th><?php etranslate("Sun")?></th>
	<?php } ?>
	<th><?php etranslate("Mon")?></th>
	<th><?php etranslate("Tue")?></th>
	<th><?php etranslate("Wed")?></th>
	<th><?php etranslate("Thu")?></th>
	<th><?php etranslate("Fri")?></th>
	<th><?php etranslate("Sat")?></th>
	<?php if ( $WEEK_START == 1 ) { ?>
		<th><?php etranslate("Sun")?></th>
	<?php } ?>
</tr>
<?php

// We add 2 hours on to the time so that the switch to DST doesn't
// throw us off.  So, all our dates are 2AM for that day.
//$sun = get_sunday_before ( $thisyear, $thismonth, 1 );
if ( $WEEK_START == 1 )
  $wkstart = get_monday_before ( $thisyear, $thismonth, 1 );
else
  $wkstart = get_sunday_before ( $thisyear, $thismonth, 1 );
// generate values for first day and last day of month
$monthstart = mktime ( 3, 0, 0, $thismonth, 1, $thisyear );
$monthend = mktime ( 3, 0, 0, $thismonth + 1, 0, $thisyear );

// debugging
//echo "<p>sun = " . date ( "D, m-d-Y", $sun ) . "</p>\n";
//echo "<p>monthstart = " . date ( "D, m-d-Y", $monthstart ) . "</p>\n";
//echo "<p>monthend = " . date ( "D, m-d-Y", $monthend ) . "</p>\n";

// NOTE: if you make HTML changes to this table, make the same changes
// to the example table in pref.php.
for ( $i = $wkstart; date ( "Ymd", $i ) <= date ( "Ymd", $monthend );
  $i += ( 24 * 3600 * 7 ) ) {
  print "<tr>\n";
  for ( $j = 0; $j < 7; $j++ ) {
    $date = $i + ( $j * 24 * 3600 );
    if ( date ( "Ymd", $date ) >= date ( "Ymd", $monthstart ) &&
      date ( "Ymd", $date ) <= date ( "Ymd", $monthend ) ) {
      $thiswday = date ( "w", $date );
      $is_weekend = ( $thiswday == 0 || $thiswday == 6 );
      if ( empty ( $WEEKENDBG ) ) $is_weekend = false;
      print "<td";
	$class = "";
      if ( date ( "Ymd", $date ) == date ( "Ymd" ) ) $class = "today";
	if ( $is_weekend ) {
		if ( strlen ( $class ) ) $class .= " ";
			$class .= "weekend";
		}
		if ( strlen ( $class ) ) echo " class=\"$class\"";
	echo ">";
       //echo date ( "D, m-d-Y H:i:s", $date ) . "<br />";
      print_date_entries ( date ( "Ymd", $date ),
        ( ! empty ( $user ) ) ? $user : $login, false );
	print "</td>\n";
    } else {
      print "<td>&nbsp;</td>\n";
    }
  }
  print "</tr>\n";
}
?></table>
<br />
<?php
	echo $eventinfo;

	display_unapproved_events ( ( $is_assistant || $is_nonuser_admin ? $user : $login ) );
?>

<br />
<a title="<?php etranslate("Generate printer-friendly version")?>" class="printer" href="#" onClick="popup('month.php?<?php
			if ( $thisyear ) {
				echo "year=$thisyear&amp;month=$thismonth&amp;";
			}
			if ( ! empty ( $user ) ) echo "user=$user&amp;";
			if ( ! empty ( $cat_id ) ) echo "cat_id=$cat_id&amp;";
		?>friendly=1', '800', '600');"><img src="imprimante.gif" alt="imprimer" border="0"></a>
<?php

if($friendly == "1")
{
	echo "<center><a href=\"#\" onClick=\"window.print();\"><img src=\"imprimante.gif\" border=\"0\" alt=\"Imprimer\"> <a href=\"#\" onClick=\"window.close();\"><img src=\"bouton_fermer.gif\" border=\"0\" alt=\"Fermer\"></center>\n";
}

print_trailer ();
?>
</body>
</html>
