<?php
$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

  $a = pg_query($dbconn, "SELECT log_transform_pastor_attendance.id as logpk, * FROM list_transform_application LEFT JOIN log_transform_pastor_attendance ON list_transform_application.id = log_transform_pastor_attendance.fk_application_pk
  where pastor_id <> log_transform_pastor_attendance.fk_pastor_pk");
  while($b = pg_fetch_array($a,NULL,PGSQL_BOTH)) {
    $application_pk = $b['fk_application_pk'];
    $inherit_pastor_pk = $b['pastor_id'];
    $logpk = $b['logpk'];

    pg_query($dbconn, "UPDATE log_transform_pastor_attendance SET fk_pastor_pk ='$inherit_pastor_pk' WHERE id = '$logpk'");

  }


?>
