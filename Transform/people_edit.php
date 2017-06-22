<?php
  include_once "_functions-general.php";
  include_once "_controller-transform.php";
  include_once "../user_session.php";
  global $username;

  $application_pk = $_GET['application_pk'];
  $participant_pk = $_GET['participant_pk'];

  $query = "SELECT *
            FROM list_transform_participant
            WHERE id = '$participant_pk'";
  $result = pg_query($db_connect, $query);
  $participant = pg_fetch_array($result,NULL,PGSQL_BOTH);

  $birthday = new DateTime($participant['birthday']);
  $birthday = $birthday->format('F d, Y');

  $last_name = $participant['last_name'];
  $first_name = $participant['first_name'];
  $middle_name = $participant['middle_name'];
  $gender = $participant['gender'];
  $birthday = $participant['birthday'];
  $contact_number = $participant['contact_number'];
  $class = $participant['category'];
  $tag = $participant['tag'];
  $participant_id = $participant['participant_id'];
  $notes = $participant['notes'];
  $score = $participant['variable2'];

  echo "
  <div class='action_bar'>
    <i class='material-icons live' title='Return' style='color:' onclick='display_people_view(\"$participant_pk\");'>navigate_before</i>
    <i class='material-icons live' title='Save' style='color:' onclick=' update_participant(\"$application_pk\",\"$participant_pk\",\"$username\");'>save</i>

    <i class='material-icons live' title='Transfer' style='color:' onclick=''>compare_arrows</i>
  </div>
  <h1>Participant Information</h1>
    <label>Last Name*</label>
      <input class='label_input' id='edit_last_name' value='$last_name' placeholder='$last_name' required><br>
    <label>First Name*</label>
      <input class='label_input' id='edit_first_name' value='$first_name' placeholder='$first_name' required><br>
    <label>Middle Name</label>
      <input class='label_input' id='edit_middle_name' value='$middle_name'><br>
    <label>Gender</label>
      <select id='edit_gender' required>
        <option value='Male'".select_option($gender, "Male").">Male</option>
        <option value='Female'".select_option($gender, "Female").">Female</option>
      </select><br/>
    <label>Birthday</label>
      <input class='label_input' id='edit_birthday' type='date' value='$birthday'><br>
    <label>Contact Number</label>
      <input class='label_input' id='edit_contact_number' value='$contact_number'><br>
    <hr>
    <h1>Program Information</h1>
    <label>ID</label>
      <span class='label_content'>$participant_id</span><br>
    <label>Score</label>
      <span class='label_content'>$score</span><br>
    <label>Class</label>
      <select id='edit_class' required>
        <option value='1' ".select_option($class, "1").">Original Participant</option>
        <option value='2' ".select_option($class, "2").">Replacement Participant (+survey +scorecard)</option>
        <option value='3' ".select_option($class, "3").">Replacement Participant (-survey +scorecard)</option>
        <option value='6' ".select_option($class, "6").">Replacement Participant (-survey -scorecard)</option>
        <option value='20' ".select_option($class, "20").">Original Counselor</option>
        <option value='21' ".select_option($class, "21").">Replacement Counselor</option>
      </select><br>
    <label>Status</label>
    <select id='edit_status' required>
      <option value='5' ".select_option($tag, "5").">Active</option>
      <option value='7' ".select_option($tag, "7").">Non-Graduate</option>
      <option value='6' ".select_option($tag, "6").">Graduated</option>
      <option value='9' ".select_option($tag, "9").">Drop</option>
    </select><br>
    <hr>
    <h1>Notes</h1>
    <input type='text' style='width:19em;' placeholder='Notes...' id='edit_notes' value='$notes'>
    <br/><br/>
    <em>*required fields</em><br/>";
  ?>
