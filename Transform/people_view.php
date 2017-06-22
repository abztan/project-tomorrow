<?php
  include_once "_functions-general.php";
  include_once "_controller-transform.php";

  $application_pk = $_GET['application_pk'];
  $participant_pk = $_GET['participant_pk'];

  $query = "SELECT *
            FROM list_transform_participant
            WHERE id = '$participant_pk'";
  $result = pg_query($db_connect, $query);
  $participant = pg_fetch_array($result,NULL,PGSQL_BOTH);

  $birthday = new DateTime($participant['birthday']);
  $birthday = $birthday->format('F d, Y');

echo "
<div class='action_bar'>
  <i class='material-icons live' title='Return' style='color:' onclick='display_people_main(\"default\");'>navigate_before</i>
  <i class='material-icons live' title='Edit' style='color:' onclick='display_people_edit(\"$participant_pk\");'>mode_edit</i>

</div>
<h1>Participant Information</h1>
  <label>Last Name</label>
    <span class='label_content'>".$participant['last_name']."</span><br>
  <label>First Name</label>
    <span class='label_content'>".$participant['first_name']."</span><br>
  <label>Middle Name</label>
    <span class='label_content'>".$participant['middle_name']."</span><br>
  <label>Gender</label>
    <span class='label_content'>".$participant['gender']."</span><br>
  <label>Birthday</label>
    <span class='label_content'>".$birthday."</span><br>
  <label>Contact Number</label>
    <span class='label_content'>".$participant['contact_number']."</span><br>
  <hr>
  <h1>Program Information</h1>
  <label>ID</label>
    <span class='label_content' title='KEY: $participant_pk'>".$participant['participant_id']."</span><br>
  <label>Score</label>
    <span class='label_content'>".$participant['variable2']."</span><br>
  <label>Class</label>
    <span class='label_content'>".get_participant_class($participant['category'])."</span><br>
  <label>Status</label>
    <span class='label_content'>".get_participant_status($participant['tag'])."</span><br>
  <hr>
  <h1>Notes</h1>
  <span class='label_content'>".($participant['notes'] != "" ? $participant['notes'] : "<em>none</em>")."</span>";
  ?>
