<?php
require_once('./initialize.php');

// 1. move all data in 'doggos' to 'all_doggos'
// 2. Compare id and disallow duplicate id

$doggos_result = find_all_in_doggos();

while($doggo = db_fetch_assoc($doggos_result)) {
  if(doggo_exists_in_all_doggos($doggo['id'])) {
    update_doggo($doggo);
  } else {
    insert_into_all_doggos($doggo);
  }
}

truncate_doggos_table();
redirect_to('./doggosearch.php');
?>
