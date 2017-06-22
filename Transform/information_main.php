<?php
  include_once "_functions-general.php";
  include_once "_controller-transform.php";

  $application_pk = $_GET['application_pk'];

  $query = "SELECT *
            FROM list_transform_application
            WHERE id = '$application_pk'";
  $result = pg_query($db_connect, $query);
  $community = pg_fetch_array($result,NULL,PGSQL_BOTH);

  $community_id = $community['community_id'];
  $community_province = $community['application_province'];
  $community_city = $community['application_city'];
  $community_barangay = $community['application_barangay'];
  $program_id = $community['application_type'];
  $location_note = $community['location_note'];
  $pastor_lname = $community['pastor_last_name'];
  $pastor_fname = $community['pastor_first_name'];
  $pastor_pk = $community['pastor_first_name'];
  $program_name = get_transform_program_name($program_id);

  $note = ("" == $location_note ? "" : "<label>Notes: $location_note</label><br/>");

  //
  // ORIGINAL - ACTIVE VS DROPOUT
  //
  $query_c = "SELECT count(*)
            FROM list_transform_participant
            WHERE fk_entry_id = '$application_pk'
            AND participant_id <> ''
            AND category = 1
            AND tag = 5";
  $result_c = pg_query($db_connect, $query_c);
  $fetch_c = pg_fetch_array($result_c,NULL,PGSQL_BOTH);
  $original_active = $fetch_c[0];

  $query_c = "SELECT count(*)
            FROM list_transform_participant
            WHERE fk_entry_id = '$application_pk'
            AND participant_id <> ''
            AND category = 1
            AND tag = 9";
  $result_c = pg_query($db_connect, $query_c);
  $fetch_c = pg_fetch_array($result_c,NULL,PGSQL_BOTH);
  $original_dropout = $fetch_c[0];

  $query_c = "SELECT count(*)
            FROM list_transform_participant
            WHERE fk_entry_id = '$application_pk'
            AND participant_id <> ''
            AND category = 1
            AND tag = 6";
  $result_c = pg_query($db_connect, $query_c);
  $fetch_c = pg_fetch_array($result_c,NULL,PGSQL_BOTH);
  $original_graduate = $fetch_c[0];

  $query_c = "SELECT count(*)
            FROM list_transform_participant
            WHERE fk_entry_id = '$application_pk'
            AND participant_id <> ''
            AND (category = 2 or category = 3 or category = 4 or category = 5 or category = 6)
            AND tag = 5";
  $result_c = pg_query($db_connect, $query_c);
  $fetch_c = pg_fetch_array($result_c,NULL,PGSQL_BOTH);
  $replacenent_active = $fetch_c[0];

  $query_c = "SELECT count(*)
            FROM list_transform_participant
            WHERE fk_entry_id = '$application_pk'
            AND participant_id <> ''
            AND (category = 2 or category = 3 or category = 4 or category = 5 or category = 6)
            AND tag = 9";
  $result_c = pg_query($db_connect, $query_c);
  $fetch_c = pg_fetch_array($result_c,NULL,PGSQL_BOTH);
  $replacenent_dropout = $fetch_c[0];

  $query_c = "SELECT count(*)
            FROM list_transform_participant
            WHERE fk_entry_id = '$application_pk'
            AND participant_id <> ''
            AND (category = 2 or category = 3 or category = 4 or category = 5 or category = 6)
            AND tag = 6";
  $result_c = pg_query($db_connect, $query_c);
  $fetch_c = pg_fetch_array($result_c,NULL,PGSQL_BOTH);
  $replacenent_graduate = $fetch_c[0];

  echo "
        <h1>Community</h1>
        <label>ID</label><span title='KEY: $application_pk'>$community_id</span><br/>
        <label>Program</label>$program_name<br/>
        <label>Location</label> $community_barangay, $community_city $community_province<br/>
        $note
        <label>Program Pastor</label> $pastor_lname, $pastor_fname<br/>
        <hr/>
        <h1>Original Participants</h1>
        <label>Active</label> $original_active<br/>
        <label>Dropout</label> $original_dropout<br/>
        <label>Graduate</label> $original_graduate<br/>
        <hr/>
        <h1>Replacement Participants</h1>
        <label>Active</label> $replacenent_active<br/>
        <label>Dropout</label> $replacenent_dropout<br/>
        <label>Graduate</label> $replacenent_graduate<br/>
        ";
?>
