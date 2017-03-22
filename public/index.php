<!DOCTYPE html>
<?php require_once('../private/initialize.php'); ?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
  
  <title>DoggoWall</title>

  <link rel="stylesheet" type="text/css" href="css/styles.css">
  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <link rel="shortcut icon" href="src/dogspotting.png">
</head>
<body>
  <div class="container">
    <h1 class="text-center">Click to view full picture!</h1><br>
    <?php
      $updating = true;
      $doggos_result = find_all_in_doggos();
      while($doggo = db_fetch_assoc($doggos_result)) {
        $updating = false;
        $group_id = substr($doggo['id'], 0, 11);
        $post_id = substr($doggo['id'], 12);
        echo '<a href="' . $doggo['src'] . '">';
        echo '<img src="' . $doggo['src'] . '" class="img-responsive" width="700"></a>';
        echo '<a href="https://www.facebook.com/groups/dogspotting/permalink/' . substr($doggo['id'], 12) . '" target="_blank" class="hidden-sm hidden-xs"><h4>Link to post</h4></a><br><br>';
        //echo '<a href="fb://facewebmodal/f?href=https://www.facebook.com/' . $group_id . '" class="hidden-md hidden-lg"><h4>Link to post</h4></a><br><br>';
        //echo '<a href="https://urlgeni.us/facebook/10487409466" class="hidden-md hidden-lg"><h4>Link to post</h4></a><br><br>';
      }
      if($updating) echo '<h3>Updating doggos... come back in a minute!</h3>';
    ?>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>
