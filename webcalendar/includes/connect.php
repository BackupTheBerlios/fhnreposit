<?php

// db settings are in config.php

// Establish a database connection.
$c = dbi_connect ( $db_host, $db_login, $db_password, $db_database );
if ( ! $c ) {
  echo "Error connecting to database:<blockquote>" .
    dbi_error () . "</blockquote>\n";
  exit;
}

// First, do a sanity check.  Make sure we can access webcal_config table.
$res = dbi_query ( "SELECT COUNT(cal_value) FROM webcal_config",
  false, false );
if ( $res ) {
  if ( $row = dbi_fetch_row ( $res ) ) {
    // Found database.  All is peachy.
    dbi_free_result ( $res );
  } else {
    // Error accessing table.
    // User has wrong db name or has not created tables.
    // Note: cannot translate this since we have not included translate.php yet.
    dbi_free_result ( $res );
    echo "<html><head><title>Database error</title>\n" .
      "</head>\n<body><h2>Database error</h2><p>" .
      "Cannot find WebCalendar tables in database '$db_database' " .
      "using db login '$db_login' on db server '$db_host'.<br/><br/>\n" .
      "Have you created the database tables as specified in the " .
      "<a href=\"docs/WebCalendar-SysAdmin.html\" target=\"other\">WebCalendar " .
      "System Administrator's Guide</a>?</p></body></html>\n";
    exit;
  }
} else {
  // Error accessing table.
  // User has wrong db name or has not created tables.
  // Note: cannot translate this since we have not included translate.php yet.
  echo "<html><head><title>Database error</title>\n" .
    "</head>\n<body><h2>Database error</h2><p>" .
    "Cannot find WebCalendar tables in database '$db_database' " .
    "using db login '$db_login' on db server '$db_host'.<br/><br/>\n" .
    "Have you created the database tables as specified in the " .
    "<a href=\"docs/WebCalendar-SysAdmin.html\" target=\"other\">WebCalendar " .
    "System Administrator's Guide</a>?</p></body></html>\n";
  exit;
}

// If we are in single user mode, make sure that the login selected is
// a valid login.
if ( $single_user == 'Y' ) {
  if ( empty ( $single_user_login ) ) {
    echo "<html><head><title>Setup error</title>\n" .
    "</head>\n<body><h2>Setup error</h2><p>" .
    "You have not defined <tt>single_user_login</tt> in " .
    "<tt>includes/settings.php</tt></p></body></html>\n";
    exit;
  }
  $res = dbi_query ( "SELECT COUNT(*) FROM webcal_user " .
    "WHERE cal_login = '$single_user_login'" );
  if ( ! $res ) {
    echo "Database error: " . dbi_error (); exit;
  }
  $row = dbi_fetch_row ( $res );
  if ( $row[0] == 0 ) {
    // User specified as single_user_login does not exist
    if ( ! dbi_query ( "INSERT INTO webcal_user ( cal_login, " .
      "cal_passwd, cal_is_admin ) VALUES ( '$single_user_login', " .
       "'" . md5($single_user_login) . "', 'Y' )" ) ) {
      echo "<b>Error:</b> user <tt>$single_user_login</tt> does not " .
      "exist in webcal_user table and was not able to add it for you:<br />" .
      dbi_error ();
      exit;
    }
    // User was added... should we tell them?
  }
  dbi_free_result ( $res );
}


// global settings have not been loaded yet, so check for public_access now
$res = dbi_query ( "SELECT cal_value FROM webcal_config " .
  "WHERE cal_setting = 'public_access'" );
$pub_acc_enabled = false;
if ( $res ) {
  if ( $row = dbi_fetch_row ( $res ) ) {
    if ( $row[0] == "Y" )
      $pub_acc_enabled = true;
  }
  dbi_free_result ( $res );
}
if ( $pub_acc_enabled ) {
  $res = dbi_query ( "SELECT cal_value FROM webcal_config " .
    "WHERE cal_setting = 'public_access_can_add'" );
  if ( $res ) {
    if ( $row = dbi_fetch_row ( $res ) ) {
      $public_access_can_add = $row[0];
    }
    dbi_free_result ( $res );
  }
}

// Debugging stuff :-)
//echo "pub_acc_enabled = " . ( $pub_acc_enabled ? "true" : "false" ) . " <br />";
//echo "session_not_found = " . ( $session_not_found ? "true" : "false" ) . " <br />";
//echo "use_http_auth = " . ( $use_http_auth ? "true" : "false" ) . " <br />";
//echo "PHP_AUTH_USER = $PHP_AUTH_USER <br />";
//echo "login = $login <br />";


if ( empty ( $PHP_SELF ) )
  $PHP_SELF = $_SERVER["PHP_SELF"];

if ( empty ( $login_url ) )
  $login_url = "login.php";
if ( strstr ( $login_url, "?" ) )
  $login_url .= "&amp;";
else
  $login_url .= "?";
if ( ! empty ( $login_return_path ) )
  $login_url .= "return_path=$login_return_path";
 

if ( $pub_acc_enabled && ! empty ( $session_not_found ) ) {
  $login = "__public__";
  $is_admin =  false;
  $lastname = "";
  $firstname = "";
  $fullname = "Public Access"; // Will be translated after translation is loaded
  $user_email = "";
} else if ( ! $pub_acc_enabled && $session_not_found && ! $use_http_auth ) {
  do_redirect ( $login_url );
  exit;
}

if ( empty ( $login ) && $use_http_auth ) {
  if ( strstr ( $PHP_SELF, "login.php" ) ) {
    // ignore since login.php will redirect to index.php
  } else {
    send_http_login ();
  }
} else if ( ! empty ( $login ) ) {
  // they are already logged in ($login is set in validate.php)
  if ( strstr ( $PHP_SELF, "login.php" ) ) {
    // ignore since login.php will redirect to index.php
  } else if ( $login == "__public__" ) {
    $is_admin =  false;
    $lastname = "";
    $firstname = "";
    $fullname = "Public Access";
    $user_email = "";
  } else {
    user_load_variables ( $login, "login_" );
    if ( ! empty ( $login_login ) ) {
      $is_admin =  ( $login_is_admin == "Y" ? true : false );
      $lastname = $login_lastname;
      $firstname = $login_firstname;
      $fullname = $login_fullname;
      $user_email = $login_email;
    } else {
      // Invalid login
      if ( $use_http_auth ) {
        send_http_login ();
      } else {
        // This shouldn't happen since login should be validated in validate.php
        // If it does happen, it means we received an invalid login cookie.
        //echo "Error getting user info for login \"$login\".";
        do_redirect ( $login_url . "&amp;error=Invalid+session+found." );
      }
    }
  }
}
//else if ( ! $single_user ) {
//  echo "Error(3)! no login info found: " . dbi_error () . "<br /><span style=\"font-weight:bold;\">SQL:</span> $sql";
//  exit;
//}

// If they are accessing using the public login, restrict them from using
// certain pages.
$not_auth = false;
if ( ! empty ( $login ) && $login == "__public__" ) {
  if ( strstr ( $PHP_SELF, "views.php" ) ||
    strstr ( $PHP_SELF, "views_edit_handler.php" ) ||
    strstr ( $PHP_SELF, "category.php" ) ||
    strstr ( $PHP_SELF, "category_handler.php" ) ||
    strstr ( $PHP_SELF, "activity_log.php" ) ||
    strstr ( $PHP_SELF, "admin.php" ) ||
    strstr ( $PHP_SELF, "adminhome.php" ) ||
    strstr ( $PHP_SELF, "admin_handler.php" ) ||
    strstr ( $PHP_SELF, "groups.php" ) ||
    strstr ( $PHP_SELF, "group_edit_handler.php" ) ||
    strstr ( $PHP_SELF, "pref.php" ) ||
    strstr ( $PHP_SELF, "pref_handler.php" ) ||
    strstr ( $PHP_SELF, "edit_user.php" ) ||
    strstr ( $PHP_SELF, "edit_user_handler.php" ) ||
    strstr ( $PHP_SELF, "approve_entry.php" ) ||
    strstr ( $PHP_SELF, "reject_entry.php" ) ||
    strstr ( $PHP_SELF, "del_entry.php" ) ||
    strstr ( $PHP_SELF, "set_entry_cat.php" ) ||
    strstr ( $PHP_SELF, "list_unapproved.php" ) ||
    strstr ( $PHP_SELF, "layers.php" ) ||
    strstr ( $PHP_SELF, "layer_toggle.php" ) ||
    strstr ( $PHP_SELF, "import.php" ) ||
    strstr ( $PHP_SELF, "import_handler.php" ) ||
    strstr ( $PHP_SELF, "edit_template.php" ) ) {
    $not_auth = true;
  }
  if ( $public_access_can_add != 'Y' ) { // do not allow add
    if ( strstr ( $PHP_SELF, "edit_entry.php" ) ||
      strstr ( $PHP_SELF, "edit_entry_handler.php" ) ) {
      $not_auth = true;
    }
  }
}

if ( empty ( $is_admin ) || ! $is_admin ) {
  if ( strstr ( $PHP_SELF, "admin.php" ) ||
    strstr ( $PHP_SELF, "admin_handler.php" ) ||
    strstr ( $PHP_SELF, "groups.php" ) ||
    strstr ( $PHP_SELF, "group_edit.php" ) ||
    strstr ( $PHP_SELF, "group_edit_handler.php" ) ||
    strstr ( $PHP_SELF, "edit_template.php" ) ||
    strstr ( $PHP_SELF, "activity_log.php" ) ) {
    $not_auth = true;
  }
}

// restrict access if calendar is read-only
if ( $readonly == "Y" ) {
  if ( strstr ( $PHP_SELF, "views.php" ) ||
    strstr ( $PHP_SELF, "views_edit_handler.php" ) ||
    strstr ( $PHP_SELF, "category.php" ) ||
    strstr ( $PHP_SELF, "category_handler.php" ) ||
    strstr ( $PHP_SELF, "groups.php" ) ||
    strstr ( $PHP_SELF, "group_edit_handler.php" ) ||
    strstr ( $PHP_SELF, "pref.php" ) ||
    strstr ( $PHP_SELF, "pref_handler.php" ) ||
    strstr ( $PHP_SELF, "import.php" ) ||
    strstr ( $PHP_SELF, "import_handler.php" ) ) {
    $not_auth = true;
  }
}

// We can't call translate() here because translate.php gets loaded
// after this include file :-(
// So, instead of an error message that may be in the wrong language,
// just redirect to some other page.
if ( $not_auth ) {
  /*
  echo "<html>\n<head>\n<title>" . translate($application_name) . " " .
    translate("Error") .  "</title>\n</head>\n<body>\n";
  echo "<h2>" . translate ( "Error" ) . "</h2>\n" .
    translate ( "You are not authorized" );
  */
  do_redirect ( "week.php" );
}

?>
