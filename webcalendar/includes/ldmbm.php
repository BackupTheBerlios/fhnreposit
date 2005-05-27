<?PHP

class ldmbm
{
  var $db_id;

  // -------------------------------------------------------------------
  
  function db_connect( $db_host, $db_login, $db_passwd, $db_base )
  {
    $this->db_id = mysql_connect($db_host, $db_login, $db_passwd) or die('Impossible de se connecter à la base de donnée');
    mysql_select_db($db_base, $this->db_id) or die('Impossible de joindre la base');
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

  // *******************************************************************
  //
  // ******************* GENERATION DE LA SELECT
  //
  // ******************************************************************* 

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
      var destList  = document.getElementById("'.$select_name.'");
      var lenTArg = TabArgV.length;
      var lenLD = destList.options.length;
      for(var i = (lenTArg-1); i >= 0; i--) { // boucle sur le nombres d arguments
        var DfDsL = false;
        for(var j = (lenLD-1); j >= 0; j--) {
          if (TabArgK[i]==destList.options[j].value) {DfDsL=true;} // element trouve
        }
        if (!DfDsL) {
          destList.options[lenLD] = new Option(TabArgV[i],TabArgK[i]);
          lenLD++;
        } // fin si element a rajouter
      } // fin boucle sur elem du tab d arguments
    } // fin function'."\n";

    echo '</script>'."\n";
    
    // ajout d'utilisateur pour édition
    while(list($key, $val) = @each($ArgUsers))
    {
      $bar .= '<option value="'.$key.'">'.$val.'</option>'."\n";
    }
    

    echo '<select name="'.$select_name.'" id="'.$select_name.'" style="width:'.$select_width.'px;" size="10" multiple>'."\n";
    echo $bar.'</select>'."\n";
    echo '<br /><a href="javascript:;" onclick="popup(\''.$popup_path.'?select_name='.$select_name.'\', \'540\', \'440\');"><img src="images_haras/b_ajouter.gif" alt="ajouter" border="0"></a> <a href="javascript:;" onclick="Suppr();"><img src="images_haras/b_supprimer.gif" alt="supprimer" border="0"></a>';

  }


  // *******************************************************************
  //
  // ************************* LA POPUP !
  //
  // ******************************************************************* 

  function popup ($select_name, $group_id)
  {
    
    // fonctions javascript
    echo '<script language="javascript">'."\n";
    echo 'function SelectionnerTout(action, selectbox) {'."\n";
    echo '  var srcList = document.getElementById(selectbox);'."\n";
    echo '  for(var i=0;i<srcList.length;i++) {'."\n";
    echo '    srcList.options[i].selected = action;'."\n";
    echo '  }'."\n";
    echo '}'."\n";
    echo 'function Confirmer() {'."\n";
    echo '  var selectcount = document.getElementById("usersok[]").length;'."\n";
    echo '  var TabArgV = new Array();'."\n";
    echo '  var TabArgK = new Array();'."\n";
    echo '  for(var i=0;i<selectcount;i++) {'."\n";
    echo '    TabArgK.push(document.getElementById("usersok[]").options[i].value);'."\n";
    echo '    TabArgV.push(document.getElementById("usersok[]").options[i].text);'."\n";
    echo '  }'."\n"; // fin boucle sur options
    echo '  window.opener.MajList(TabArgK,TabArgV);'."\n";
    echo '}'."\n";
    echo 'function Add() {'."\n";
    echo '  var destList  = document.getElementById("usersok[]");'."\n";
    echo '  var srcList  = document.getElementById("users");'."\n";
    echo '  var len = srcList.options.length;'."\n";
    echo '  for(var i = (len-1); i >= 0; i--) {'."\n";
    echo '    if ((srcList.options[i] != null) && (srcList.options[i].selected == true)) {'."\n";
    echo '      destList.options[destList.options.length] = new Option(srcList.options[i].text, srcList.options[i].value);'."\n";
    echo '    }'."\n";
    echo '  }'."\n";
    echo '}'."\n";
    echo 'function Suppr() {'."\n";
    echo '  var destList = document.getElementById("usersok[]");'."\n";
    echo '  var len = destList.options.length;'."\n";
    echo '  for(var i = (len-1); i >= 0; i--) {'."\n";
    echo '    if ((destList.options[i] != null) && (destList.options[i].selected == true)) {'."\n";
    echo '      destList.options[i] = null;'."\n";
    echo '    }'."\n";
    echo '  }'."\n";
    echo '}'."\n";
    echo '</script>'."\n";

    // Affiche la combo des groupes
    echo '<form name="userform" id="userform" method="POST" action="">'."\n";
    echo '<table width="100%"><tr><td>'."\n";
    echo '<b>Rechercher un nom :</b><br>'."\n";
    echo '<input name="search" type="text" onfocus="SelectionnerTout(\'true\', \'usersok[]\');"> <input type="image" src="images_haras/b_rech.gif" border="0" onclick="SelectionnerTout(\'true\', \'usersok[]\');"><br><br>'."\n";
    echo '<b>S&eacute;lectionnez un groupe :</b><br />'."\n";
    echo '<select name="groups" style="width:230px;" onChange="document.userform.action=\'?select_name='.$select_name.'&group_id=\' + this.options[selectedIndex].value;SelectionnerTout(\'true\', \'usersok[]\');document.userform.submit();">'."\n";
    
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
    
    echo '<select id="users" style="width:230px;" size="15" multiple>'."\n";


    // ##############
    // RECHERCHE
    // ##############

    if($_POST['search'])
    {
      $result = $this->db_query('SELECT webcal_group_user.cal_login, webcal_user.cal_firstname, webcal_user.cal_lastname FROM webcal_group_user, webcal_user WHERE webcal_group_user.cal_login = webcal_user.cal_login AND (webcal_user.cal_lastname like \'%'.$_POST['search'].'%\' OR webcal_user.cal_firstname like \'%'.$_POST['search'].'%\') ORDER BY webcal_user.cal_lastname ASC');

      // On cherche dans les utilisateurs normaux
      // ----------------------------------------

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

      // On cherche dans les non utilisateurs
      // ------------------------------------

      $result = $this->db_query('SELECT * FROM webcal_nonuser_cals WHERE cal_lastname like \'%'.$_POST['search'].'%\' OR cal_firstname like \'%'.$_POST['search'].'%\' ORDER BY cal_lastname ASC');

      if($result && $this->db_num_rows($result))
      {
	$tmp = array();
        while( $data = $this->db_fetch_array($result) )
        {
                echo '<option value="'.$data['cal_login'].'">'.$data['cal_lastname'].' '.$data['cal_firstname'].'</option>'."\n";
        }
       }

    }
    else // pas de recherche donc liste des users du groupe
    {
        // les utilisateurs normaux
        $result = $this->db_query('SELECT webcal_group_user.cal_login, webcal_user.cal_firstname, webcal_user.cal_lastname FROM webcal_group_user, webcal_user WHERE webcal_group_user.cal_login = webcal_user.cal_login AND webcal_group_user.cal_group_id ='.$group_id.' ORDER BY webcal_user.cal_lastname ASC');
        if($result && $this->db_num_rows($result))
        {
                while( $data = $this->db_fetch_array($result) )
                {
                  echo '<option value="'.$data['cal_login'].'">'.$data['cal_lastname'].' '.$data['cal_firstname'].'</option>'."\n";
                }
        }
        // les non users
        $result = $this->db_query('SELECT webcal_group_user.cal_login, webcal_nonuser_cals.cal_firstname, webcal_nonuser_cals.cal_lastname FROM webcal_nonuser_cals,webcal_group_user WHERE webcal_group_user.cal_login = webcal_nonuser_cals.cal_login AND webcal_group_user.cal_group_id ='.$group_id.' ORDER BY webcal_nonuser_cals.cal_lastname ASC');
        if($result && $this->db_num_rows($result))
        {
                while( $data = $this->db_fetch_array($result) )
                {
                  echo '<option value="'.$data['cal_login'].'">'.$data['cal_lastname'].' '.$data['cal_firstname'].'</option>'."\n";
                }
        }
    }
    echo '</select>'."\n";
     

    echo '<br><center><a href="#" onclick="SelectionnerTout(\'true\', \'users\');"><img src="images_haras/b_toutselectionner.gif" alt="Tout Sélectionner" border="0"</a></center>'."\n";
    echo '</td><td align="center">'."\n"; 
    echo '<input type="button" value=">>" onclick="Add();"><br><br><input type="button" value="<<" onclick="Suppr();">'."\n";

    echo '</td><td valign="bottom">'."\n"; 
    echo '<b>Votre sélection :</b><br>'."\n";
    echo '<select id="usersok[]" name="usersok[]" style="width:230px;" size="14" multiple>'."\n";

    // On re-récupère les noms des users déjà ajoutés
    if($_POST['usersok'])
    {
      foreach($_POST['usersok'] as $key )
      {
        // non utilisateurs ?
        if(ereg("_NUC_", $key))
        {
                $req = $this->db_query("SELECT * FROM webcal_nonuser_cals WHERE cal_login='$key'");
                $row = $this->db_fetch_array($req);
                echo '<option value="'.$key.'">'.$row[2]. ' '.$row[1].'</option>'."\n";
        }
        else // utilisateur normaux
        {
                $req = $this->db_query("SELECT cal_firstname, cal_lastname FROM webcal_user WHERE cal_login='$key'");
                $row = $this->db_fetch_array($req);
                echo '<option value="'.$key.'">'.$row['cal_lastname']. ' '.$row['cal_firstname'].'</option>'."\n";
        }
      }
    }

    echo '</select><br>'."\n";
    echo '<center><br><a href="#" onclick="Confirmer();window.close();"><img src="images_haras/b_confirmer.gif" alt="confirmer" border="0"></a></center>';
    echo '</td></tr></table>'."\n";
    echo '</form>'."\n";

    $this->db_close();

  } // fin de la fonction
} // fin de class
