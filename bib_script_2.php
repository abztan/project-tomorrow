<?php
include_once "db/db_connect.php";

  $query = "
select log_bib_payment.id, log_bib_payment.fk_application_pk as child_pk, list_bib_community.fk_application_pk as master_pk
from log_bib_payment
left join list_bib_community
on log_bib_payment.fk_bib_community_pk = list_bib_community.id";
  $result = pg_query($db_connect, $query);


  while($bib = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
    $master_pk = $bib['master_pk'];
    $child_pk = $bib['child_pk'];
    $id = $bib['id'];


    if($child_pk != $master_pk) {
      echo "$id ...$child_pk = $master_pk <br/>";
        //  echo $query_x = "UPDATE log_bib_payment SET fk_application_pk = '$master_pk' WHERE id = '$id'";

          //$result_x = pg_query($db_connect,$query_x);
    }

  }
?>
