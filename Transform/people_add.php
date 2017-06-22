<?php
  include_once "_functions-general.php";
  include_once "_controller-transform.php";
  include_once "../user_session.php";
  global $username;

  $application_pk = $_GET['application_pk'];
  $application = get_application_details($application_pk);

echo "
  <div class='action_bar'>
    <i class='material-icons live' title='Return' style='color:' onclick='display_people_main(\"default\");'>navigate_before</i>
    <i class='material-icons live' title='Save' style='color:' onclick=' add_participant(\"$username\");'>save</i>
  </div>

  <h1>Add Participant</h1>
  <label>Name</label>
  <input required type='text' id='add_last_name' placeholder='Last Name (required)'>,
  <input required type='text' id='add_first_name' placeholder='First Name (required)'>
  <input type='text' id='add_middle_name' placeholder='Middle Name'> <br/>

  <label>Gender</label>
  <select required id='add_gender' class=''>
    <option value='Male'>Male</option>
    <option value='Female'>Female</option>
  </select><br/>

  <label>Birthday</label> <input type='date' id='add_birthday'><br/>

  <label>Contact Number</label> <input type='number' id='add_contact_number' placeholder='09228000000' min='0' value=''><br/>

  <label>Class</label>

  <select required class='' name='' id='add_class'>
    <option value='1'>Original Participant</option>
    <option value='2'>Replacement Participant (+survey +scorecard)</option>
    <option value='3'>Replacement Participant (-survey +scorecard)</option>
    <option value='6'>Replacement Participant (-survey -scorecard)</option>
    <option value='20'>Original Counselor</option>
    <option value='21'>Replacement Counselor</option>
  </select><br/>

  <label>Status</label> <select required class='' name='' id='add_status'>
    <option value='5'>Active</option>
    <option value='7'>Non-Graduate</option>
    <option value='6'>Graduated</option>
    <option value='9'>Drop</option>
  </select><br/>

  <label>Notes</label> <input type='text' id='add_notes' placeholder='Add notes...'><br/><br/>

  <label>Next Available ID</label>".generate_participant_id($application_pk,$application['community_id'])."
  <br/><br/>
  <span style='color:#e05b4a' id='notify'><em></em></span>";

?>
