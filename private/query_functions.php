<?php

  function insert_doggo($doggo) {
    global $db;

    $sql = "INSERT INTO doggos ";
    $sql .= "(id, src, reaction_count)";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $doggo['id']) . "',";
    $sql .= "'" . db_escape($db, $doggo['src']) . "',";
    $sql .= "'" . db_escape($db, $doggo['reaction_count']) . "'";
    $sql .= ");";

    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  function truncate_doggos_table() {
    global $db;

    $sql = "truncate doggos;";

    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  function insert_all_doggos() {
    global $db;

    $sql = "INSERT INTO all_doggos ";
    $sql .= "(id, src, reaction_count, created_at) ";
    $sql .= "SELECT * FROM doggos;";

    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  function find_top_in_doggos() {
    global $db;

    $sql = "SELECT * FROM doggos;";

    $doggos_result = db_query($db, $sql);
    return $doggos_result;
  }

?>
