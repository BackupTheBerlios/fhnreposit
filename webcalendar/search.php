<?php
	include_once 'includes/init.php';

	if ( $groups_enabled == "Y" )
		$INC = array('js/search.php');
	print_header($INC);
?>
<h2><?php 
	etranslate("Search"); 
?></h2>

<form action="search_handler.php" method="post" name="searchformentry" style="margin-left:13px;">

<label for="keywordsadv" style="font-weight:bold;"><?php etranslate("Keywords")?>:&nbsp;</label>
<input type="text" name="keywords" id="keywordsadv" size="30" />&nbsp;
<input type="submit" value="<?php etranslate("Search")?>" /><br />
<?php 
	if ( ($login == "__public__" && $public_access_others != "Y") || (! $is_admin) ) {
		echo "</form>";
	} else {
		echo "<div id=\"advlink\"><a href=\"javascript:show('adv'); hide('advlink');\">" . translate("Advanced Search") . "</a></div>";
?>
<table id="adv" style="display:none;">
<tr><td style="vertical-align:top; text-align:right; font-weight:bold; width:60px;">
	<?php etranslate("Users"); ?>:&nbsp;</td><td>
<?php
  $users = get_my_users ();
  $size = 0;
  $out = "";
  for ( $i = 0; $i < count ( $users ); $i++ ) {
    $out .= "<option value=\"" . $users[$i]['cal_login'] . "\"";
    if ( $users[$i]['cal_login'] == $login )
      $out .= " selected=\"selected\"";
    $out .= ">" . $users[$i]['cal_fullname'] . "</option>\n";
  }
  if ( count ( $users ) > 50 )
    $size = 15;
  else if ( count ( $users ) > 10 )
    $size = 10;
  else
    $size = count ( $users );
?>
<select name="users[]" size="<?php echo $size;?>" multiple="multiple"><?php echo $out; ?></select>
<?php 
  if ( $groups_enabled == "Y" ) {
   echo "<input type=\"button\" onclick=\"selectUsers()\" value=\"" .
      translate("Select") . "...\" />\n";
  }
?>
</td></tr>
</table>
</form>
<?php } ?>

<?php print_trailer(); ?>
</body>
</html>
