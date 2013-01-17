<?php

  require_once('dbconnect.php');

  $db = DbUtil::loginConnection();
  $stmt = $db -> stmt_init();

  if($stmt -> prepare('DELETE FROM active_queue') or die (mysqli_error($db))) {
    $stmt -> execute();
    $db -> commit();
  }

  $stmt -> close();
  $db -> close();


  echo '<button onclick=close_alert() class="close">×</button>';
  echo 'Successfully removed all students from queue.';
  
?>