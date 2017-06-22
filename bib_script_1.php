<?php
include_once "db/db_connect.php";

  $query = "SELECT *
            from list_transform_application
            where community_id IS NOT NULL";
  $result = pg_query($db_connect, $query);


  while($jess = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
    $pk = $jess['id'];
    $community_id = $jess['community_id'];
    $year = substr($community_id,0,2);
    $batch = substr($community_id,5,1);
    $by = $year."B".$batch;

    echo "$community_id x $year x $batch <br/>";
    echo $query_x = "UPDATE list_transform_application SET batchyear = '$by' WHERE id = '$pk'";
    echo "<br/>";
    $result_x = pg_query($db_connect,$query_x);
  }
?>
