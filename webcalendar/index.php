<?php
include_once 'includes/init.php';

//echo "user: $user et login: $login";

// S'il n'est pas anonyme
if ( $login != '__public__' ) {
		do_redirect ( empty ( $STARTVIEW ) ? "month.php" : "$STARTVIEW.php" );
}
else
{
	do_redirect ("login.php");
}

// If not yet logged in, you will be redirected to login.php before
// we get to this point (by connect.php included above)
//do_redirect ( empty ( $STARTVIEW ) ? "month.php" : "$STARTVIEW.php" );
?>
