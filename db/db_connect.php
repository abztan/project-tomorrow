<?php
  $connect_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
  $db_connect = pg_connect($connect_string) or die("Can't connect to database".pg_last_error());
  global $connect_string;
  global $db_connect;
?>
