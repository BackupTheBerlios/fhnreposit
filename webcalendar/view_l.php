<?php
/*
 * $Id: view_l.php,v 1.2 2005/05/27 10:11:43 vmaury Exp $
 *
 * Page Description:
 *	This page will display the month "view" with all users's events
 *	on the same calendar.  (The other month "view" displays each user
 *	calendar in a separate column, side-by-side.)  This view gives you
 *	the same effect as enabling layers, but with layers you can only
 *	have one configuration of users.
 *
 * Input Parameters:
 *	id (*) - specify view id in webcal_view table
 *	date - specify the starting date of the view.
 *	  If not specified, current date will be used.
 *	friendly - if set to 1, then page does not include links or
 *	  trailer navigation.
 *	(*) required field
 *
 * Security:
 *	Must have "allow view others" enabled ($allow_view_other) in
 *	  System Settings unless the user is an admin user ($is_admin).
 *	Must be owner of the view.
 */

if($friendly == "1") $popup = true;
include_once 'includes/init.php';

$error = "";

if ( $allow_view_other == "N" && ! $is_admin ) {
  // not allowed...
  do_redirect ( "$STARTVIEW.php" );
}
if ( empty ( $id ) ) {
  do_redirect ( "views.php" );
}

// Find view name in $views[]
$view_name = "";
for ( $i = 0; $i < count ( $views ); $i++ ) {
  if ( $views[$i]['cal_view_id'] == $id ) {
    $view_name = $views[$i]['cal_name'];
  }
}

// If view_name not found, then the specified view id does not
// belong to current user. 
if ( empty ( $view_name ) ) {
  $error = translate ( "You are not authorized" );
}

$INC = array('js/popups.php');
print_header($INC);

set_today($date);

$next = mktime ( 3, 0, 0, $thismonth + 1, 1, $thisyear );
$nextyear = date ( "Y", $next );
$nextmonth = date ( "m", $next );
$nextdate = sprintf ( "%04d%02d01", $nextyear, $nextmonth );

$prev = mktime ( 3, 0, 0, $thismonth - 1, 1, $thisyear );
$prevyear = date ( "Y", $prev );
$prevmonth = date ( "m", $prev );
$prevdate = sprintf ( "%04d%02d01", $prevyear, $prevmonth );

if ( ! empty ( $bold_days_in_year ) && $bold_days_in_year == 'Y' ) {
	$boldDays = true;
	$startdate = sprintf ( "%04d%02d01", $prevyear, $prevmonth );
	$enddate = sprintf ( "%04d%02d31", $nextyear, $nextmonth );
} else {
	$boldDays = false;
	$startdate = sprintf ( "%04d%02d01", $thisyear, $thismonth );
	$enddate = sprintf ( "%04d%02d31", $thisyear, $thismonth );
}

$monthstart = mktime ( 3, 0, 0, $thismonth, 1, $thisyear );
$monthend = mktime ( 3, 0, 0, $thismonth + 1, 0, $thisyear );

$thisdate = $startdate;

// get users in this view
$res = dbi_query (
  "SELECT cal_login FROM webcal_view_user WHERE cal_view_id = $id" );
$viewusers = array ();
if ( $res ) {
  while ( $row = dbi_fetch_row ( $res ) ) {
    $viewusers[] = $row[0]; 
  }
  dbi_free_result ( $res );
} else {
  $error = translate ( "Database error" ) . ": " . dbi_error ();
}
if ( count ( $viewusers ) == 0 ) {
  // no need to translate the following since it should not happen
  // unless the db gets screwed up.
  $error = "No users for this view";
}

if ( ! empty ( $error ) ) {
  echo "<h2>" . translate ( "Error" ) .
    "</h2>\n" . $error;
  print_trailer ();
  exit;
}

$e_save = array ();
$re_save = array ();
for ( $i = 0; $i < count ( $viewusers ); $i++ ) {
  /* Pre-Load the repeated events for quckier access */
  $repeated_events = read_repeated_events ( $viewusers[$i], "", $startdate ); 
  $re_save = array_merge($re_save, $repeated_events);
  /* Pre-load the non-repeating events for quicker access */
  $events = read_events ( $viewusers[$i], $startdate, $enddate );
  $e_save = array_merge($e_save, $events);
} 
$events = array ();
$repeated_events = array ();

for ( $i = 0; $i < count ( $e_save ); $i++ ) {
  $should_add = 1;
  for ( $j = 0; $j < count ( $events ) && $should_add; $j++ ) {
    if ( $e_save[$i]['cal_id'] == $events[$j]['cal_id'] ) {
      $should_add = 0;
    }
  }
  if ( $should_add ) {
    array_push ( $events, $e_save[$i] );
  }
}

for ( $i = 0; $i < count ( $re_save ); $i++ ) {
  $should_add = 1;
  for ( $j = 0; $j < count ( $repeated_events ) && $should_add; $j++ ) {
    if ( $re_save[$i]['cal_id'] == $repeated_events[$j]['cal_id'] ) {
      $should_add = 0;
    }
  }
  if ( $should_add ) {
    array_push ( $repeated_events, $re_save[$i] );
  }
}

display_small_month ( $prevmonth, $prevyear, true, true, "prevmonth", "view_l.php?id=$id&amp;" );
display_small_month ( $nextmonth, $nextyear, true, true, "nextmonth", "view_l.php?id=$id&amp;" );
?>

<div class="title">
<span class="date"><br /><?php
  echo date_to_str ( sprintf ( "%04d%02d01", $thisyear, $thismonth ),
    $DATE_FORMAT_MY, false, false );
?></span>
<span class="viewname"><br /><?php
    echo $view_name;
?></span></div>
<br /><br /><br /><br />

<table class="main" style="clear:both;" cellspacing="0" cellpadding="0">
<tr>
<?php if ( $WEEK_START == 0 ) { ?>
<th class="weekend"><?php etranslate("Sun")?></th>
<?php } ?>
<th><?php etranslate("Mon")?></th>
<th><?php etranslate("Tue")?></th>
<th><?php etranslate("Wed")?></th>
<th><?php etranslate("Thu")?></th>
<th><?php etranslate("Fri")?></th>
<th class="weekend"><?php etranslate("Sat")?></th>
<?php if ( $WEEK_START == 1 ) { ?>
<th class="weekend"><?php etranslate("Sun")?></th>
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
//echo "<br /><br />sun = " . date ( "D, m-d-Y", $sun ) . "<br />";
//echo "<br /><br />monthstart = " . date ( "D, m-d-Y", $monthstart ) . "<br />";
//echo "<br /><br />monthend = " . date ( "D, m-d-Y", $monthend ) . "<br />";

//NOTE: if you make HTML changes to this table, make the same changes
//to the example table in pref.php.
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
	if ( $date == date ( "Ymd", $today ) ) {
		echo " class=\"today\">";
	} elseif ( $is_weekend ) {
		echo " class=\"weekend\">";
	} else {
		echo ">";
	}
	//echo date ( "D, m-d-Y H:i:s", $date ) . "<br />";
      print_date_entries ( date ( "Ymd", $date ),
        ( ! empty ( $user ) ) ? $user : $login, false, implode(',', $viewusers) );
      print "</td>\n";
    } else {
      print "<td>&nbsp;</td>\n";
    }
  }
  print "</tr>\n";
}
?>
</table><br />

<?php
  echo $eventinfo;
  display_unapproved_events ( ( $is_assistant || $is_nonuser_admin ? $user : $login ) );
?>

<br /> 

<?PHP
if($friendly == "1")
{
	echo "<center><a href=\"#\" onClick=\"window.print();\"><img src=\"imprimante.gif\" border=\"0\" alt=\"Imprimer\"> <a href=\"#\" onClick=\"window.close();\"><img src=\"bouton_fermer.gif\" border=\"0\" alt=\"Fermer\"></center>\n";
}
else
{
	if ( $thisyear ) {
		$tmp .= "year=$thisyear&amp;month=$thismonth&amp;";
	}
	if ( ! empty ( $user ) ) $tmp .= "user=$user&amp;";
	if ( ! empty ( $cat_id ) ) $tmp .= "cat_id=$cat_id&amp;";

	echo "<a title=\"" . translate("Generate printer-friendly version") . "\" class=\"printer\" href=\"#\" onClick=\"popup('view_l.php?id=$id&amp;$tmp&amp;friendly=1', '800', '600');\"><img src=\"imprimante.gif\" alt=\"imprimer\" border=\"0\"></a>\n";
}

print_trailer ();?>
</body>
</html>
