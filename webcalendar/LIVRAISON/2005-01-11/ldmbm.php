<?PHP

class ldmbm
{

  var $db_host   = 'localhost';
  var $db_login   = 'root';
  var $db_passwd  = '';
  var $db_base  = '';
  var $db_id  = '';

  // -------------------------------------------------------------------
  
  function db_connect()
  {
    $this->db_id = mysql_connect($this->db_host, $this->db_login, $this->db_passwd) or die('Impossible de se connecter √† la base de donn√©e');
    mysql_select_db($this->db_base, $this->db_id) or die('Impossible de joindre la base');
  }

  function db_query($foo)
  {
    return mysql_query($foo, $this->db_id);
  }

  function db_fetch_array($foo)
  {
    return mysql_fetch_array($foo);
  }
  
  function db_num_rows($foo)
  {
    return mysql_num_rows($foo);
  }

  function db_close()
  {
    mysql_close($this->db_id);
  }

  // -------------------------------------------------------------------

  function generate_select ($select_name, $select_width, $popup_path, $ArgUsers)
  {

    // script pour ajouter dans le menu parent
    echo '<script language="javascript">'."\n";
    echo 'function Suppr() {'."\n";
    echo '  var destList  = document.getElementById("'.$select_name.'");'."\n";
    echo '  var len = destList.options.length;'."\n";
    echo '  for(var i = (len-1); i >= 0; i--) {'."\n";
    echo '    if ((destList.options[i] != null) && (destList.options[i].selected == true)) {'."\n";
    echo '      destList.options[i] = null;'."\n";
    echo '    }'."\n";
    echo '  }'."\n";
    echo '}'."\n";

    echo '  var TabArgK = new Array();'."\n";
    echo '  var TabArgV = new Array();'."\n";

    echo 'function MajList(TabArgK,TabArgV) {
      //alert (\'Tableaux \' + TabArgK.length + TabArgV.length );
      var destList  = document.getElementById("'.$select_name.'");
      var lenTArg = TabArgV.length;
      var lenLD = destList.options.length;
      for(var i = (lenTArg-1); i >= 0; i--) { // boucle sur le nombres d arguments
        var DfDsL = false;
        for(var j = (lenLD-1); j >= 0; j--) {
//          alert (\' liste ex : \' + destList.options[j].value + destList.options[j].text + \'param \' + TabArgK[i] + TabArgV[i]);
          if (TabArgK[i]==destList.options[j].value) {DfDsL=true;} // element trouve
          }
        if (!DfDsL) {
          destList.options[lenLD] = new Option(TabArgV[i],TabArgK[i]);
          lenLD++;
        } // fin si element a rajouter
        } // fin boucle sur elem du tab d arguments
    } // fin function'."\n";

    echo '</script>'."\n";
    
    // ajout d'utilisateur pour Èdition
    while(list($key, $val) = @each($ArgUsers))
    {
      $bar .= '<option value="'.$key.'">'.$val.'</option>'."\n";
    }
    

    echo '<select name="'.$select_name.'" id="'.$select_name.'" style="width:'.$select_width.'px;" size="10" multiple>'."\n";
    echo $bar.'</select>'."\n";
    echo '<br /><a href="javascript:;" onclick="popup(\''.$popup_path.'?select_name='.$select_name.'\', \'280\', \'600\');">ajouter</a> / <a href="javascript:;" onclick="Suppr();">enlever</a>';

  }

  function popup ($select_name, $group_id)
  {
  
    $this->db_connect();
    
    // Affiche la combo des groupes
    echo '<center>'."\n";
    echo '<form method="POST" action="">'."\n";
    echo '<b>Rechercher un nom :</b><br>'."\n";
    echo '<input name="search" type="text"> <input type="submit" value="Go"><br>'."\n";
    echo '</form>'."\n";
    echo 'ou'."\n";
    echo '<form>'."\n";
    echo '<b>S&eacute;lectionnez un groupe :</b><br />'."\n";
    echo '<select name="groups" style="width:250px;" onChange="document.location.href = \'?select_name='.$select_name.'&group_id=\' + this.options[selectedIndex].value;">'."\n";
    
    // est ce que
    if($group_id) 
    {
      echo '<option disabled>Faites votre choix</option>'."\n";
    }
    else
    {
      echo '<option disabled selected>Faites votre choix</option>'."\n";
    }
    
    $result = $this->db_query('SELECT * FROM webcal_group ORDER BY cal_name ASC');
    if($result && $this->db_num_rows($result))
    {  
      while( $data = $this->db_fetch_array($result) )
      {
        // Selectionner le bon
        if($group_id == $data['cal_group_id'])
        {
          echo '<option value="'.$data['cal_group_id'].'" selected>'.$data['cal_name'].'</option>'."\n";
        }
        else
        {
          echo '<option value="'.$data['cal_group_id'].'">'.$data['cal_name'].'</option>'."\n";
        }
      }
    }
    echo '</select><br />'."\n";
    
    //affiche les utilisateurs du groupe selectionne
    
    echo '<form name="userform"><select name="users" style="width:250px;" size="20" multiple>'."\n";


    // recherche ou pas ?
    if($_POST['search'])
    {
      $result = $this->db_query('SELECT webcal_group_user.cal_login, webcal_user.cal_firstname, webcal_user.cal_lastname FROM webcal_group_user, webcal_user WHERE webcal_group_user.cal_login = webcal_user.cal_login AND webcal_user.cal_lastname like \'%'.$_POST['search'].'%\' ORDER BY webcal_user.cal_lastname ASC');

      if($result && $this->db_num_rows($result))
      {
	$tmp = array();
        while( $data = $this->db_fetch_array($result) )
        {
	  $found = 0;
	  // comme il y a plusieurs fois la meme entre -> tableau temporaire
          foreach($tmp as $key => $value)
          { 
            if($value == $data['cal_login']) $found=1;
          } 
	  
  	  // donc pas dans le tableau, on ajoute
	  if($found == 0) 
	  {
	    echo '<option value="'.$data['cal_login'].'">'.$data['cal_lastname'].' '.$data['cal_firstname'].'</option>'."\n";
	    array_push($tmp, $data['cal_login']);
	  }
  	  
        } // FIN DU WHILE
      } // FIN DU NUM ROW
    }
    else
    {
      $result = $this->db_query('SELECT webcal_group_user.cal_login, webcal_user.cal_firstname, webcal_user.cal_lastname FROM webcal_group_user, webcal_user WHERE webcal_group_user.cal_login = webcal_user.cal_login AND webcal_group_user.cal_group_id ='.$group_id.' ORDER BY webcal_user.cal_lastname ASC');
      if($result && $this->db_num_rows($result))
      {
        while( $data = $this->db_fetch_array($result) )
        {
          echo '<option value="'.$data['cal_login'].'">'.$data['cal_lastname'].' '.$data['cal_firstname'].'</option>'."\n";
        }
      }
    }
    echo '</select></form>'."\n";
    

    // script pour (d√©)selectionner tout
    echo '<script language="javascript">'."\n";
    echo 'function SetSelectOptions(action) {'."\n";
    echo '  var selectcount = document.forms[0].users.length;'."\n";
    echo '  for(var i=0;i<selectcount;i++) {'."\n";
    echo '    document.forms[0].users.options[i].selected = action;'."\n";
    echo '  }'."\n";
    echo '}'."\n";
    echo '</script>'."\n";
    


    echo '<script language="javascript">'."\n";
    echo 'function AddSelected() {'."\n";
    echo '  var selectcount = document.forms[0].users.length;'."\n";
    echo '  var TabArgV = new Array();'."\n";
    echo '  var TabArgK = new Array();'."\n";
    echo '  for(var i=0;i<selectcount;i++) {'."\n";
    echo '    if(document.forms[0].users.options[i].selected==true) {'."\n";
    echo '      TabArgK.push(document.forms[0].users.options[i].value);'."\n";
    echo '      TabArgV.push(document.forms[0].users.options[i].text);'."\n";

// mÈthode Ben avec mise ‡ jour de la popup
//    echo '      var sname   = document.forms[0].users.options[i].text;'."\n";
//    echo '      var soption   = document.forms[0].users.options[i]; '."\n";
//    echo '      var parentcount = window.opener.document.getElementById("'.$select_name.'").options.length;'."\n";
//    echo '      window.opener.document.getElementById("'.$select_name.'").options[parentcount] = new Option(sname, soption);'."\n";

    echo '   }'."\n"; // fin si option selectionnÈe
    echo '  }'."\n"; // fin boucle sur options
// DEBUG  echo ' alert (\'Tableaux \' + TabArgK.length + TabArgV.length );';
    echo '      window.opener.MajList(TabArgK,TabArgV);'."\n";

    echo '}'."\n";
    echo '</script>'."\n";    
    
    // tout selectionner et valider
    echo '<a href="#" onclick="SetSelectOptions(\'true\');">Tout S&eacute;lectionner</a><br /><br/>'."\n";
    echo '<a href="#" onclick="AddSelected();window.close();"><h4>Ajouter !</h4></a></center>';
    echo '</form>'."\n";

    $this->db_close();
  }
  
}
