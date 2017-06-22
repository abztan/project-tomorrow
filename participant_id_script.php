<?php
include_once "/db/db_connect.php";
function generate_participant_id($application_pk,$community_id) {
  global $db_connect;
  $query = "SELECT max(participant_id)
          FROM list_transform_participant
          WHERE fk_entry_id = '$application_pk'";
  $result = pg_query($db_connect, $query);
  $row = pg_fetch_array($result,NULL,PGSQL_BOTH);
  $max_id = $row['0'];

  if($max_id  != "")
  {
    $participant_count = substr($max_id, -2);
    $participant_count = $participant_count+1;
  }
  else
    $participant_count = "1";

  $participant_count = str_pad($participant_count, 2, 0, STR_PAD_LEFT);

  $next_participant_id = $community_id.$participant_count;

  return $next_participant_id;
}

$application_pk = "3092";

  $query = "SELECT list_transform_application.community_id, list_transform_participant.id as participant_pk
            FROM list_transform_application
            LEFT JOIN list_transform_participant
            ON list_transform_application.id = list_transform_participant.fk_entry_id
            WHERE fk_entry_id = '$application_pk'
            AND list_transform_participant.tag <> '3'

            ORDER BY list_transform_participant.last_name, list_transform_participant.first_name";
  $result = pg_query($db_connect, $query);


  while($participant = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
    $participant_pk = $participant['participant_pk'];
    $community_id = $participant['community_id'];

    $new_participant_id = generate_participant_id($application_pk,$community_id);


    echo $query_x = "UPDATE list_transform_participant SET participant_id = '$new_participant_id' WHERE id = '$participant_pk'";
    echo "<br/>";
    $result_x = pg_query($db_connect,$query_x);
  }
?>
