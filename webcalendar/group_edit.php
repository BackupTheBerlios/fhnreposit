<?php
$popup=1;
include_once 'includes/init.php';
print_header();
?>

<form action="group_edit_handler.php" method="post">
<?php
$newgroup = true;
$groupname = "";
$groupowner = "";
$groupupdated = "";


if ( empty ( $id ) ) {
  $groupname = translate("Unnamed Group");
} else {
  $newgroup = false;
  // get group by id
  $res = dbi_query ( "SELECT cal_owner, cal_name, cal_last_update, cal_owner " .
    "FROM webcal_group WHERE cal_group_id = $id" );
  if ( $res ) {
    if ( $row = dbi_fetch_row ( $res ) ) {
      $groupname = $row[1];
      $groupupdated = $row[2];
      user_load_variables ( $row[3], "temp" );
      $groupowner = $tempfullname;
    }
    dbi_fetch_row ( $res );
  }
}


if ( $newgroup ) {
  $v = array ();
  echo "<h2>" . translate("Add Group") . "</h2>\n";
  echo "<input type=\"hidden\" name=\"add\" value=\"1\" />\n";
} else {
  echo "<h2>" . translate("Edit Group") . "</h2>\n";
  echo "<input name=\"id\" type=\"hidden\" value=\"$id\" />";
}
?>

<table style="border-width:0px;">
<tr><td style="font-weight:bold;">
	<label for="groupname"><?php etranslate("Group name")?>:</label></td><td>
	<input type="text" name="groupname" id="groupname" size="20" value="<?php echo htmlspecialchars ( $groupname );?>" />
</td></tr>
<?php if ( ! $newgroup ) { ?>
	<tr><td style="vertical-align:top; font-weight:bold;">
		<?php etranslate("Updated"); ?>:</td><td>
		<?php echo date_to_str ( $groupupdated ); ?>
	</td></tr>
	<tr><td style="vertical-align:top; font-weight:bold;">
		<?php etranslate("Created by"); ?>:</td><td>
		<?php echo $groupowner; ?>
	</td></tr>
<?php } ?>
<tr><td style="vertical-align:top; font-weight:bold;">
	<label for="users"><?php etranslate("Users"); ?>:</label></td><td>

<?php
  // get list of all users
  $users = user_get_users ();
  if ($nonuser_enabled == "Y" ) {
    $nonusers = get_nonuser_cals ();
    $users = ($nonuser_at_top == "Y") ? array_merge($nonusers, $users) : array_merge($users, $nonusers);
  }

    echo '<script language="javascript">'."\n";
    echo 'function SelectionnerTout(action, selectbox) {'."\n";
    echo '  var srcList = document.getElementById(selectbox);'."\n";
    echo '  for(var i=0;i<srcList.length;i++) {'."\n";
    echo '    srcList.options[i].selected = action;'."\n";
    echo '  }'."\n";
    echo '}'."\n";
    echo 'function AddUser() {'."\n";
    echo '  var destList  = document.getElementById("users");'."\n";
    echo '  var srcList  = document.getElementById("listusers");'."\n";



    echo '  var len = srcList.options.length;'."\n";
    echo '  for(var i = (len-1); i >= 0; i--) {'."\n";
    echo '    if ((srcList.options[i] != null) && (srcList.options[i].selected == true)) {'."\n";
    echo '      find=false;'."\n";
    echo '      for(var j=0;j<destList.length;j++) {'."\n";
    echo '        if(destList.options[j].value == srcList.options[i].value) { find=true; }'."\n";
    echo '      }'."\n";
    echo '      if(find==false) {'."\n";
    echo '        destList.options[destList.options.length] = new Option(srcList.options[i].text, srcList.options[i].value);'."\n";
    echo '      }'."\n";



    echo '    }'."\n";
    echo '  }'."\n";
    echo '}'."\n";
    echo 'function SupprUser() {'."\n";
    echo '  var destList = document.getElementById("users");'."\n";
    echo '  var len = destList.options.length;'."\n";
    echo '  for(var i = (len-1); i >= 0; i--) {'."\n";
    echo '    if ((destList.options[i] != null) && (destList.options[i].selected == true)) {'."\n";
    echo '      destList.options[i] = null;'."\n";
    echo '    }'."\n";
    echo '  }'."\n";
    echo '}'."\n";
    echo '</script>'."\n";


?>

<table border="0">
<tr><td>
<select name="listusers[]" id="listusers" size="10" multiple="multiple">
<?php

  for ( $i = 0; $i < count ( $users ); $i++ ) {
    $u = $users[$i]['cal_login'];
    echo "<option value=\"$u\">" . $users[$i]['cal_fullname'] . "</option>\n";
  }
?>
</select>
</td><td>
<input type="button" value=">>" onclick="AddUser();"><br><br><input type="button" value="<<" onclick="SupprUser();">
</td><td>
<select name="users[]" id="users" size="10" multiple="multiple" style="width:210px;">
<?php
        $res = dbi_query('SELECT webcal_group_user.cal_login, webcal_user.cal_firstname, webcal_user.cal_lastname FROM webcal_group_user, webcal_user WHERE webcal_group_user.cal_login = webcal_user.cal_login AND webcal_group_user.cal_group_id ='.$id.' ORDER BY webcal_user.cal_lastname ASC');
        if($res)
        {
                while( $data = dbi_fetch_row($res) )
                {
                  echo '<option value="'.$data[0].'">'.$data[1].' '.$data[2].'</option>'."\n";
                }
        }
        // les non users
        $res = dbi_query('SELECT webcal_group_user.cal_login, webcal_nonuser_cals.cal_firstname, webcal_nonuser_cals.cal_lastname FROM webcal_nonuser_cals,webcal_group_user WHERE webcal_group_user.cal_login = webcal_nonuser_cals.cal_login AND webcal_group_user.cal_group_id ='.$id.' ORDER BY webcal_nonuser_cals.cal_lastname ASC');
        if($res)
        {
                while( $data = dbi_fetch_row($res) )
                {
                  echo '<option value="'.$data[0].'">'.$data[1].' '.$data[2].'</option>'."\n";
                }
        }

?>
</select>
</td></tr></table>

</td></tr>
<tr><td colspan="2" style="text-align:center;">
	<br /><input type="submit" name="action" onfocus="SelectionnerTout(true, 'users');" value="<?php if ( $newgroup ) etranslate("Add"); else etranslate("Save"); ?>" />
	<?php if ( ! $newgroup ) { ?>
		<input type="submit" name="action" value="<?php etranslate("Delete")?>" onclick="return confirm('<?php etranslate("Are you sure you want to delete this entry?"); ?>')" />
	<?php } ?>
</td></tr>
</table>
</form>

</body>
</html>
