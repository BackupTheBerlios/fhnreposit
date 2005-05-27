<html>
  <header>
    <title>Selection des utilisateurs</title>
  </header>
  <body>

<?PHP
include('includes/ldmbm.php');
include('includes/config.php');

$ldmbm = new ldmbm;
$ldmbm->db_connect($db_host, $db_login, $db_password, $db_database);
$ldmbm->popup($_GET['select_name'], $_GET['group_id']);

?>

  </body>
</html>
