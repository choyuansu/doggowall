<?php
  // doggos functions
  function find_in_doggos($id) {
    global $db;

    $sql = "SELECT * FROM doggos ";
    $sql .= "WHRER id='" . $id . "' LIMIT 1;";
    $doggo_result = db_query($db, $sql);
    return $doggo_result;
  }

  function insert_into_doggos($doggo) {
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

    $sql = "TRUNCATE doggos;";

    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  function doggo_exists_in_doggos($id) {
    $result = find_in_doggos($id);
    $doggo = db_fetch_assoc($result);
    return !empty($doggo);
  }

  function find_all_in_doggos() {
    global $db;

    $sql = "SELECT * FROM doggos;";

    $doggos_result = db_query($db, $sql);
    return $doggos_result;
  }

  // all_doggos functions
  function find_in_all_doggos($id) {
    global $db;

    $sql = "SELECT * FROM all_doggos ";
    $sql .= "WHERE id='" . $id . "' LIMIT 1;";
    $doggo_result = db_query($db, $sql);
    return $doggo_result;
  }
  
  function insert_into_all_doggos($doggo) {
    global $db;

    $sql = "INSERT INTO all_doggos ";
    $sql .= "(id, src, reaction_count, created_at) ";
    $sql .= "SELECT * FROM doggos ";
    $sql .= "WHERE id='" . $doggo['id'] . "';";

    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  function doggo_exists_in_all_doggos($id) {
    $result = find_in_all_doggos($id);
    $doggo = db_fetch_assoc($result);
    return !empty($doggo);
  }

  function update_doggo($doggo) {
    global $db;

    $sql = "UPDATE all_doggos SET ";
    $sql .= "reaction_count='" . $doggo['reaction_count'] . "' ";
    $sql .= "WHERE id='" . $doggo['id'] . "' ";
    $sql .= "LIMIT 1;";
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      echo db_error($db);
      db_close($db);
      exit;
    }
  }
?>
