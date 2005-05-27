<?php
include_once 'includes/init.php';

if ( ! $is_admin )
  $user = $login;

$INC = array('js/views_edit.php');
print_header($INC);
?>

<form action="views_edit_handler.php" method="post" name="editviewform"  onsubmit="SetSelectOptions();">

<?php

$newview = true;
$viewname = "";
$viewtype = "";


if ( empty ( $id ) ) {
  $viewname = translate("Unnamed View");
} else {
  // search for view by id
  for ( $i = 0; $i < count ( $views ); $i++ ) {
    if ( $views[$i]['cal_view_id'] == $id ) {
      $newview = false;
      $viewname = $views[$i]["cal_name"];
      if ( empty ( $viewname ) )
        $viewname = translate("Unnamed View");
      $viewtype = $views[$i]["cal_view_type"];
    }
  }
}


if ( $newview ) {
  $v = array ();
  echo "<h2>" . translate("Add View") . "</h2>\n";
  echo "<input type=\"hidden\" name=\"add\" value=\"1\" />\n";
} else {
  echo "<h2>" . translate("Edit View") . "</h2>\n";
  echo "<input name=\"id\" type=\"hidden\" value=\"$id\" />\n";
}
?>

<table style="border-width:0px;">
<tr><td style="font-weight:bold;"><?php etranslate("View Name")?>:</td>
  <td><input name="viewname" size="20" value="<?php echo htmlspecialchars ( $viewname );?>" /></td></tr>
<tr><td style="font-weight:bold;"><?php etranslate("View Type")?>:</td>
  <td><select name="viewtype">
      <option value="D" <?php if ( $viewtype == "D" ) echo " selected=\"selected\"";?>><?php etranslate("Day"); ?></option>
      <option value="W" <?php if ( $viewtype == "W" ) echo " selected=\"selected\"";?>><?php etranslate("Week (Users horizontal)"); ?></option>
      <option value="V" <?php if ( $viewtype == "V" ) echo " selected=\"selected\"";?>><?php etranslate("Week (Users vertical)"); ?></option>
      <option value="M" <?php if ( $viewtype == "M" ) echo " selected=\"selected\"";?>><?php etranslate("Month (side by side)"); ?></option>
      <option value="L" <?php if ( $viewtype == "L" ) echo " selected=\"selected\"";?>><?php etranslate("Month (on same calendar)"); ?></option>
      </select>
      </td></tr>
<tr><td style="verical-align:top; font-weight:bold;">
<?php etranslate("Users"); ?>:</td>
<td>

<?PHP

// -----------------------------------------------------------------------------------------
// -- Gestion d'ajout d'utilisateurs. Dev Artec

  // get list of all users
  $users = get_my_users ();
  if ($nonuser_enabled == "Y" ) {
    $nonusers = get_nonuser_cals ();
    $users = ($nonuser_at_top == "Y") ? array_merge($nonusers, $users) : array_merge($users, $nonusers);
  }
  // get list of users for this view
  if ( ! $newview ) {
    $sql = "SELECT cal_login FROM webcal_view_user WHERE cal_view_id = $id";
    $res = dbi_query ( $sql );
    if ( $res ) {
      while ( $row = dbi_fetch_row ( $res ) ) {
        $viewuser[$row[0]] = 1;
      }
      dbi_free_result ( $res );
    }
  }
  
for ( $i = 0; $i < count ( $users ); $i++ ) {
    $u = $users[$i]['cal_login'];
    if ( ! empty ( $viewuser[$u] ) ) {
      $ArgUsers[$u] = $users[$i]['cal_fullname'] ;
    }	
	
}

// script pour (dÃ©)selectionner tout
echo '<script language="javascript">'."\n";
echo 'function SetSelectOptions() {'."\n";
echo '  var selectcount = document.forms["editviewform"].elements[3].length;'."\n";
echo '  for(var i=0;i<selectcount;i++) {'."\n";
echo '    document.forms["editviewform"].elements[3].options[i].selected = true;'."\n";
echo '  }'."\n";
echo '}'."\n";
echo '</script>'."\n";	


include('includes/ldmbm.php');
$ldmbm = new ldmbm;
$ldmbm->generate_select('users[]', '255', 'popup_ldmbm.php', $ArgUsers);
// penser à modifier le petit javascript qui selectionne toute la liste sur le bouton input


// -----------------------------------------------------------------------------------------
?>



</td></tr>
<tr><td colspan="2">
<br /><br />
<div style="text-align:center;">
<input type="submit" name="action" value="<?php if ( $newview ) etranslate("Add"); else etranslate("Save"); ?>" />
<?php if ( ! $newview ) { ?>
<input type="submit" name="action" value="<?php etranslate("Delete")?>" onclick="return confirm('<?php etranslate("Are you sure you want to delete this entry?"); ?>')" />
<?php } ?>
</div>
</td></tr>
</table>

</form>

<?php print_trailer(); ?>
</body>
</html>
