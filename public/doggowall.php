<!DOCTYPE html>
<head>
  <?php echo '<link rel="stylesheet" type="text/css" href="./styles.css">'; ?>
</head>
<body>
<?php
require_once('../private/initialize.php');
//truncate_doggos_table();
$doggos_result = find_top_in_doggos();
echo '<h2>Click image to go to post!</h2>';
while($doggo = db_fetch_assoc($doggos_result)) {
  echo '<a href="http://www.facebook.com/' . $doggo['id'] . '">';
  echo '<img src="' . $doggo['src'] . '"></a><br><br>';
}
?>
</body>