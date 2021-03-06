<?php
include_once 'includes/init.php';

$error = "";

$updating_public = false;;
if ( $is_admin && ! empty ( $public ) && $public_access == "Y" ) {
  $updating_public = true;
  $prefuser = "__public__";
} elseif (($user != $login) && ($is_admin || $is_nonuser_admin)) {
  $prefuser = "$user";
} else {
  $prefuser = "$login";
}

while ( list ( $key, $value ) = each ( $HTTP_POST_VARS ) ) {
  $setting = substr ( $key, 5 );
  $prefix = substr ( $key, 0, 5 );
  //echo "Setting = $setting, key = $key, prefix = $prefix <br />\n";
  if ( strlen ( $setting ) > 0 && $prefix == "pref_" ) {
    $sql =
      "DELETE FROM webcal_user_pref WHERE cal_login = '$prefuser' " .
      "AND cal_setting = '$setting'";
    dbi_query ( $sql );
    if ( strlen ( $value ) > 0 ) {
      $sql = "INSERT INTO webcal_user_pref " .
        "( cal_login, cal_setting, cal_value ) VALUES " .
        "( '$prefuser', '$setting', '$value' )";
      if ( ! dbi_query ( $sql ) ) {
        $error = "Unable to update preference: " . dbi_error () .
	"<br /><br /><span style=\"font-weight:bold;\">SQL:</span> $sql";
        break;
      }
    }
  }
}

if ( empty ( $error ) ) {
    do_redirect ( "adminhome.php" );
}
print_header();
?>

<h2><?php etranslate("Error")?></h2>

<?php etranslate("The following error occurred")?>:
<blockquote>
<?php echo $error; ?>
</blockquote>

<?php print_trailer(); ?>

</body>
</html>
