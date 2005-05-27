<?php

/*--------------------------------------------------------------------
 init.php written by Jeff Hoover
 - simplifies script initialization
 - puts HTML headers in an easy to call function

 ** NOTE that the following scripts do not use this file:
  - login.php
  - week_ssi.php
  - tools/send_reminders.php

 How to use:
 1. call include_once 'includes/init.php'; at the top of your script.
 2. call any other functions or includes not in this file that you need
 3. call the print_header function with proper arguments

 What gets called:

  include_once 'includes/config.php';
  include_once 'includes/php-dbi.php';
  include_once 'includes/functions.php';
  include_once "includes/$user_inc";
  include_once 'includes/validate.php';
  include_once 'includes/connect.php';
  load_global_settings ();
  load_user_preferences ();
  include_once 'includes/translate.php';
  include_once 'includes/styles.php';

 Also, for month.php, day.php, week.php, week_details.php:

  send_no_cache_header ();

//--------------------------------------------------------------------
*/

session_start();

$debut = explode(" ",microtime());
$debut = $debut[1]+$debut[0];

// Get script name
$self = $_SERVER['PHP_SELF'];
if ( empty ( $self ) )
  $self = $PHP_SELF;
preg_match ( "/\/(\w+\.php)/", $self, $match);
$SCRIPT = $match[1];

// Several files need a no-cache header and some of the same code
$special = array('month.php', 'day.php', 'week.php', 'week_details.php');
$DMW = in_array($SCRIPT, $special);

// Unset some variables that shouldn't be set
unset($user_inc);
 
include_once 'includes/config.php';
include_once 'includes/php-dbi.php';
include_once 'includes/functions.php';
include_once "includes/$user_inc";
include_once 'includes/validate.php';
include_once 'includes/connect.php';


load_global_settings ();

if ( empty ( $ovrd ) )
   load_user_preferences ();

include_once 'includes/translate.php';

// error-check some commonly used form variable names
$id = getValue ( "id", "[0-9]+", true );
$user = getValue ( "user", "[A-Za-z0-9_\.=@,\-]+", true );
$date = getValue ( "date", "[0-9]+" );
$year = getValue ( "year", "[0-9]+" );
$month = getValue ( "month", "[0-9]+" );
$hour = getValue ( "hour", "[0-9]+" );
$minute = getValue ( "minute", "[0-9]+" );
$cat_id = getValue ( "cat_id", "[0-9]+" );
$friendly = getValue ( "friendly", "[01]" );

// Load if $SCRIPT is in $special array:
if ($DMW) {
  
  // Tell the browser not to cache
  send_no_cache_header ();

  if ( empty ( $user ) )
    remember_this_view ();

  if ( $allow_view_other != 'Y' && ! $is_admin )
    $user = "";

  $can_add = ( $readonly == "N" || $is_admin == "Y" );
  if ( $public_access == "Y" && $login == "__public__" ) {
    if ( $public_access_can_add != "Y" )
      $can_add = false;
    if ( $public_access_others != "Y" )
      $user = ""; // security precaution
  }

  if ( ! empty ( $user ) ) {
    $u_url = "user=$user&amp;";
    user_load_variables ( $user, "user_" );
    if ( $user == "__public__" )
      $user_fullname = translate ( $PUBLIC_ACCESS_FULLNAME );
  } else {
    $u_url = "";
    $user_fullname = $fullname;
    if ( $login == "__public__" )
      $user_fullname = translate ( $PUBLIC_ACCESS_FULLNAME );
  }

  set_today($date);

  //if ( $categories_enabled == "Y" && ( !$user || $user == $login ) ) {
  if ( $categories_enabled == "Y" ) {
    if ( ! empty ( $cat_id ) ) {
      $cat_id = $cat_id;
    } elseif ( ! empty ( $CATEGORY_VIEW ) ) {
      $cat_id = $CATEGORY_VIEW;
    } else {
      $cat_id = '';
    }
  } else {
    $cat_id = '';
  }
  if ( empty ( $cat_id ) )
    $caturl = "";
  else
    $caturl = "&amp;cat_id=$cat_id";
}

// Maps page filenames to the id that page's <body> tag will have
$bodyid = array(
  "activity_log.php" => "activitylog",
  "add_entry.php" => "addentry",
  "admin.php" => "admin",
  "adminhome.php" => "adminhome",
  "approve_entry.php" => "approveentry",
  "assistant_edit.php" => "assistantedit",
  "category.php" => "category",
  "day.php" => "day",
  "del_entry.php" => "delentry",
  "del_layer.php" => "dellayer",
  "edit_entry.php" => "editentry",
  "edit_layer.php" => "editlayer",
  "edit_report.php" => "editreport",
  "edit_template.php" => "edittemplate",
  "edit_user.php" => "edituser",
  "edit_user_handler.php" => "edituserhandler",
  "export.php" => "export",
  "group_edit.php" => "groupedit",
  "group_edit_handler.php" => "groupedithandler",
  "groups.php" => "groups",
  "help_admin.php" => "helpadmin",
  "help_bug.php" => "helpbug",
  "help_edit_entry.php" => "helpeditentry",
  "help_import.php" => "helpimport",
  "help_index.php" => "helpindex",
  "help_layers.php" => "helplayers",
  "help_pref.php" => "helppref",
  "import.php" => "import",
  "index.php" => "index",
  "layers.php" => "layers",
  "layers_toggle.php" => "layerstoggle",
  "list_unapproved.php" => "listunapproved",
  "login.php" => "login",
  "month.php" => "month",
  "nonusers.php" => "nonusers",
  "pref.php" => "pref",
  "publish.php" => "publish",
  "purge.php" => "purge",
  "reject_entry.php" => "rejectentry",
  "report.php" => "report",
  "search.php" => "search",
  "select_user.php" => "selectuser",
  "set_entry_cat.php" => "setentrycat",
  "users.php" => "users",
  "usersel.php" => "usersel",
  "view_d.php" => "viewd",
  "view_entry.php" => "viewentry",
  "view_l.php" => "viewl",
  "view_m.php" => "viewm",
  "view_t.php" => "viewt",
  "view_v.php" => "viewv",
  "view_w.php" => "vieww",
  "views.php" => "views",
  "views_edit.php" => "viewsedit",
  "week.php" => "week",
  "week_details.php" => "weekdetails",
  "week_ssi.php" => "weekssi",
  "year.php" => "year"
);

// Prints the HTML header and opening Body tag.
//      $includes - an array of additional files to include referenced from
//                  the includes directory
//      $HeadX - a variable containing any other data to be printed inside
//               the head tag (META, SCRIPT, etc)
//      $BodyX - a variable containing any other data to be printed inside
//               the Body tag (onload for example)
//  $disableCustom - do not include custom header (useful for small
//    popup windows, such as color selection)
//  $disableStyle - do not include the standard css
function print_header($includes = '', $HeadX = '', $BodyX = '',
  $disableCustom=false, $disableStyle=false) {
  global $application_name;
  global $FONTS,$WEEKENDBG,$THFG,$THBG;
  global $TABLECELLFG,$TODAYCELLBG,$TEXTCOLOR;
  global $POPUP_FG,$BGCOLOR;
  global $LANGUAGE;
  global $CUSTOM_HEADER, $CUSTOM_SCRIPT;
  global $friendly;
  global $bodyid, $self, $popup;  
  global $CUSTOM_TRAILER, $c, $STARTVIEW;
  global $login, $user, $cat_id, $categories_enabled, $thisyear,
    $thismonth, $thisday, $DATE_FORMAT_MY, $WEEK_START, $DATE_FORMAT_MD,
    $readonly, $is_admin, $public_access, $public_access_can_add,
    $single_user, $use_http_auth, $login_return_path, $require_approvals,
    $is_nonuser_admin, $public_access_others, $allow_view_other,
    $views, $reports_enabled, $LAYER_STATUS, $nonuser_enabled,
    $groups_enabled, $fullname, $has_boss;



  $lang = '';
  if ( ! empty ( $LANGUAGE ) )
    $lang = languageToAbbrev ( $LANGUAGE );
  if ( empty ( $lang ) )
    $lang = 'en';

 // Start the header & specify the charset
 // The charset is defined in the translation file
 if ( ! empty ( $LANGUAGE ) ) {
   $charset = translate ( "charset" );
   if ( $charset != "charset" ) {
  echo "<?xml version=\"1.0\" encoding=\"$charset\"?>
<!DOCTYPE html
  PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
  \"DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"$lang\" lang=\"$lang\">
<head>
  <title>".translate($application_name)."</title>\n";
   } else {
  echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<!DOCTYPE html
  PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
  \"DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<head>
  <title>".translate($application_name)."</title>\n";
   }
 }

 // Any other includes?
 if ( is_array ( $includes ) ) {
   foreach( $includes as $inc ){
     include_once 'includes/'.$inc;
   }
 }

  // Do we need anything else inside the header tag?
  if ($HeadX) echo $HeadX."\n";

  // Include the styles
  if ( ! $disableStyle ) {
    echo '<link rel="stylesheet" type="text/css" href="includes/styles.php">';
  }

  // Add custom script/stylesheet if enabled
  if ( $CUSTOM_SCRIPT == 'Y' && ! $disableCustom ) {
    $res = dbi_query (
      "SELECT cal_template_text FROM webcal_report_template " .
      "WHERE cal_template_type = 'S' and cal_report_id = 0" );
    if ( $res ) {
      if ( $row = dbi_fetch_row ( $res ) ) {
        echo $row[0];
      }
      dbi_free_result ( $res );
    }
  }

  // Include includes/print_styles.css as a media="print" stylesheet. When the
  // user clicks on the "Printer Friendly" link, $friendly will be non-empty,
  // including this as a normal stylesheet so they can see how it will look 
  // when printed. This maintains backwards-compatibility for browsers that 
  // don't support media="print" stylesheets
  echo "<link rel=\"stylesheet\" type=\"text/css\"" . ( empty ( $friendly ) ? " media=\"print\"" : "" ) . " href=\"includes/print_styles.css\" />\n";

  // Link to favicon
  echo "<link rel=\"shortcut icon\" href=\"favicon.ico\" type=\"image/x-icon\" />\n";

  // encodage
  echo '<meta http-equiv="Content-Type" Content="text/html; charset=ISO-8859-1">'."\n";

// fonctions vincent
include_once 'includes/fonctions_vincent.php';
JSpopup();
  
  
  // Finish the header
  echo "</head>\n<body";

  // Find the filename of this page and give the <body> tag the corresponding id
  $thisPage = substr($self, strrpos($self, '/') + 1);
  if ( isset( $bodyid[$thisPage] ) )
    echo " id=\"" . $bodyid[$thisPage] . "\"";

  // Add any extra parts to the <body> tag
  if ( ! empty( $BodyX ) )
    echo " $BodyX";
        // ajout du bandeau haras nationnaux
  
  if($popup == true) {
    echo ">\n";
  } else {
    echo " style=\"background-image:url(images_haras/fond_bandeau.jpg);background-repeat:no-repeat;\">\n";
  }

  // Add custom header if enabled
  if ( $CUSTOM_HEADER == 'Y' && ! $disableCustom ) {
    $res = dbi_query (
      "SELECT cal_template_text FROM webcal_report_template " .
      "WHERE cal_template_type = 'H' and cal_report_id = 0" );
    if ( $res ) {
      if ( $row = dbi_fetch_row ( $res ) ) {
        echo $row[0];
      }
      dbi_free_result ( $res );
    }
  }


    
  // bandeau haras nationnaux
  if($popup != true) {
  ?>

  <!-- <a href="http://xinf-prodlinux/hn2g/"><div style="position:fixed;top:30px;width:300px;height:70px;"></div></a> -->
<script>
function modPform(type) {
//alert (type);
if (type=='annuHN') {
   window.document.searchformentry.target='_new';
   window.document.searchformentry.action='http://xinf-prodlinux/intra_drh/html/list_pers.php?lc_prmev[ro]=C&lc_prmev[typers]=I&lc_prmev[aff_pop]=Y&tf_PER_LLNOMPERS=INPLIKE&cfrf=true&tf_DRH_LLACTIVITE=LDMEG&sp_DRH_LLACTIVITE=ACTN:CPA:MTT:CMAT:CMATNR';
   }
else {
      window.document.searchformentry.target='_self';
   window.document.searchformentry.action='search_handler.php';
    }
}

</script>  
  <div class="bandeauharas">
  <form action="search_handler.php" name="searchformentry" method="POST" style="margin:0px;">
    recherche <input class="gris11px"  onfocus="modPform('intsearch')" type="text" style="width:100px" name="keywords" size="20"><img title="rechercher avec le moteur de recherche" src="images_haras/puce_rechercher.gif" alt="lancer la recherche" height="19" width="21" align="absmiddle" border="0" onclick="modPform('intsearch'); document.searchformentry.submit();"><img src="images_haras/separation_haut.gif" alt="" height="19" width="13" align="absmiddle" border="0">
    annuaire HN&nbsp;<input class="gris11px" onfocus="modPform('annuHN')" type="text" style="width:100px" name="rq_PER_LLNOMPERS" size="20"><img title="rechercher dans l'annuaire des HN" src="images_haras/puce_rechercher.gif" alt="" height="19" width="21" align="absmiddle" border="0" onclick="modPform('annuHN'); window.document.searchformentry.submit()"><a href="http://xinf-prodlinux/intra_drh/html/req_rech_pers.php?prech=CI&lc_prmev[aff_pop]=Y" target=_"blank">
    <img title="recherche avancée dans l'annuaire des HN" src="images_haras/puce_avance.gif" alt="" name="puce_avancee2" height="19" width="62" align="absmiddle" border="0"></a>
<?PHP

// les vues
echo '<img src="images_haras/separation_haut.gif" alt="" height="19" width="13" align="absmiddle" border="0">';

if ( ( $login != "__public__" ) && ( $allow_view_other != "N" || $is_admin ) ) {
  ?>
  <select name="Vues" size="0" onchange="if(this.value) { location.href = this.value; }">
  <option disabled selected>Vos vues</option>
  <option value="views.php">Gérer vos vues</option>
  <option disabled value="">----------</option>
  <?PHP

  for ( $i = 0; $i < count ( $views ); $i++ ) {
    echo '<option value="';
    if ( $views[$i]['cal_view_type'] == 'W' )
      echo "view_w.php?";
    elseif ( $views[$i]['cal_view_type'] == 'D' )
      echo "view_d.php?";
    elseif ( $views[$i]['cal_view_type'] == 'V' )
      echo "view_v.php?";
    elseif ( $views[$i]['cal_view_type'] == 'T' )
      echo "view_t.php?timeb=0&amp;";
    elseif ( $views[$i]['cal_view_type'] == 'M' )
      echo "view_m.php?";
    elseif ( $views[$i]['cal_view_type'] == 'L' )
      echo "view_l.php?";
    elseif ( $views[$i]['cal_view_type'] == 'S' )
      echo "view_t.php?timeb=1&amp;";
    else
      echo "view_m.php?";
    echo "id=" . $views[$i]['cal_view_id'];
    if ( ! empty ( $thisdate ) )
      echo "&amp;date=$thisdate";

    echo '">'.$views[$i]['cal_name'].'</option>';
  }
  echo '</select>';
 
} else {
     echo '<select disabled="disabled"><option>Vos vues</option></select>';
}

if ( $login != '__public__' ) {
  echo '<img src="images_haras/separation_haut.gif" alt="" height="19" width="13" align="absmiddle" border="0"><a class="grisorange" href="import.php"  title="Importez et exporter votre calendrier">Import / Export</a>';
} else {
  echo '<img src="images_haras/separation_haut.gif" alt="" height="19" width="13" align="absmiddle" border="0">Import / Export';
}


?>
<img src="images_haras/separation_haut.gif" alt="" height="19" width="13" align="absmiddle" border="0"><a class="grisorange" href="#" onclick="window.open ( 'help_index.php', 'cal_help', 'dependent,menubar,scrollbars,height=400,width=400,innerHeight=420,outerWidth=420' );" onmouseover="window.status='Aide'"  title="Obtenir de l'aide">Aide</a>
<?
if ( $is_admin ) {
  echo '<img src="images_haras/separation_haut.gif" alt="" height="19" width="13" align="absmiddle" border="0"><a class="grisorange" href="adminhome.php" title="Accéder À l\'espace d\'administration">Admin</a>';
} else {
      echo '<img src="images_haras/separation_haut.gif" alt="" height="19" width="13" align="absmiddle" border="0">Admin';
}




  ?>
  </span>
</form>

  </div>
  <div style="top:45px;left:400px;position:absolute;font-size:12px;">
    <div id="menu">
  <?php
  $can_add = ( $readonly == "N" || $is_admin == "Y" );
  if ( $public_access == "Y" && $public_access_can_add != "Y" &&
    $login == "__public__" )
    $can_add = false;

  if ( ! empty ( $GLOBALS['STARTVIEW'] ) )
    $mycal = $GLOBALS['STARTVIEW'].".php";
  else
    $mycal = "index.php";


  if ( $single_user != "Y" ) {
    if ( ! empty ( $user ) && $user != $login )
      echo "<a title=\"Retour Ã  mon agenda\" style=\"font-weight:bold;\" href=\"$mycal\">" . 
  translate("Back to My Calendar") . "</a>";
    else
      echo "<a title=\"" . 
  translate("My Calendar") . "\" style=\"font-weight:bold;\" href=\"$mycal\">" . 
  translate("My Calendar") . "</a>\n";

    if ( ! empty ( $user ) && $user != $login )
      $todayURL .= '?user=' . $user;
    echo " | <a title=\"Visualiser la journée en cours\" style=\"font-weight:bold;\" href=\"day.php\">" . 
  translate("Today") . "</a>\n";
    if ( $login != "__public__" && $readonly == "N" &&
      ( $require_approvals == "Y" || $public_access == "Y" ) ) {
  $url = 'list_unapproved.php';
        if ($is_nonuser_admin) $url .= "?user=$user";
  echo " | <a title=\"Permet de valider les évènement en attente d'approbation\" style=\"font-weight:bold;\" href=\"$url\">" . 
    translate("Unapproved Events") . "</a>\n";
    }

    if ( $login == "__public__" && $public_access_others != "Y" ) {
      // don't allow them to see other people's calendar
    } else if ( $allow_view_other == "Y" || $is_admin )
    
      //echo " | <a title=\"" . 
    translate("Another User's Calendar") . "\" href=\"select_user.php\">" . 
    translate("Another User's Calendar") . "</a>\n";
    
  } else {
    echo "<a title=\"" . 
    translate("My Calendar") . "\" style=\"font-weight:bold;\" href=\"$mycal\">" . 
    translate("My Calendar") . "</a>\n";
    echo " | <a title=\"" . 
    translate("Today") . "\" style=\"font-weight:bold;\" href=\"$todayURL\">" . 
    translate("Today") . "</a>\n";
  }
  // only display some links if we're viewing our own calendar.
  if ( empty ( $user ) || $user == $login ) {
    if ( $can_add ) {
      echo " | <a title=\"Ajouter un évènement\" style=\"font-weight:bold;\" href=\"edit_entry.php";
      if ( ! empty ( $thisyear ) ) {
        print "?year=$thisyear";
        if ( ! empty ( $thismonth ) )
          print "&amp;month=$thismonth";
        if ( ! empty ( $thisday ) )
          print "&amp;day=$thisday";
      }
      echo "\">" . 
  translate("Add New Entry") . "</a>\n";
    }
  }
  if ( empty ( $user ) || $user == $login ) {
    if ( $login != '__public__' ) {
      echo " | <a title=\"Déconnexion\" style=\"font-weight:bold;\" href=\"login.php\">Déconnexion</a>\n";
    } else {
      echo " | <a title=\"Déconnexion\" style=\"font-weight:bold;\" href=\"login.php\">Connexion</a>\n"; 
    }       
  }
?>

     </div>
  </div>
  <div style="padding-top:100px;">

  <?php
  }
}


// Print the common trailer.
// Include custom trailer if enabled
function print_trailer ( $include_nav_links=true, $closeDb=true,
  $disableCustom=false )
{
  /*
  global $CUSTOM_TRAILER, $c, $STARTVIEW;
  global $login, $user, $cat_id, $categories_enabled, $thisyear,
    $thismonth, $thisday, $DATE_FORMAT_MY, $WEEK_START, $DATE_FORMAT_MD,
    $readonly, $is_admin, $public_access, $public_access_can_add,
    $single_user, $use_http_auth, $login_return_path, $require_approvals,
    $is_nonuser_admin, $public_access_others, $allow_view_other,
    $views, $reports_enabled, $LAYER_STATUS, $nonuser_enabled,
    $groups_enabled, $fullname, $has_boss;
  
  if ( $include_nav_links ) {
    include_once "includes/trailer.php";
  }

  // Add custom trailer if enabled
  if ( $CUSTOM_TRAILER == 'Y' && ! $disableCustom && isset ( $c ) ) {
    $res = dbi_query (
      "SELECT cal_template_text FROM webcal_report_template " .
      "WHERE cal_template_type = 'T' and cal_report_id = 0" );
    if ( $res ) {
      if ( $row = dbi_fetch_row ( $res ) ) {
        echo $row[0];
      }
      dbi_free_result ( $res );
    }
  }

  if ( $closeDb ) {
    if ( isset ( $c ) )
      dbi_close ( $c );
    unset ( $c );
  }
  */
  global $debut;
  $fin = explode(" ",microtime());
  $fin = $fin[1]+$fin[0];
  $temps_passe = $fin-$debut; 
  echo "<!-- $temps_passe -->\n";
}
?>
