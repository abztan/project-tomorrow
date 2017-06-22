<?php
  include_once "../db/db_connect.php";

  function get_participant_status($tag) {
    if(1 == $tag ) {
      $value = "New";
    }
    else if(2 == $tag ) {
      $value = "Qualified";
    }
    else if(3 == $tag ) {
      $value = "Disqualified";
    }
    else if(4 == $tag ) {
      $value = "Flagged";
    }
    else if(5 == $tag ) {
      $value = "Active";
    }
    else if(6 == $tag ) {
      $value = "Graduated";
    }
    else if(7 == $tag ) {
      $value = "Non-graduate";
    }
    else if(8 == $tag ) {
      $value = "Ghost";
    }
    else if(9 == $tag ) {
      $value = "Dropped";
    }

    return $value;
  }

  function get_participant_status_icon($tag) {
    if(1 == $tag ) {
      $value = "new_release";
    }
    else if(2 == $tag ) {
      $value = "sentiment_satisfied";
    }
    else if(3 == $tag ) {
      $value = "sentiment_dissatisfied";
    }
    else if(4 == $tag ) {
      $value = "priority_high";
    }
    else if(5 == $tag ) {
      $value = "star_border";
    }
    else if(6 == $tag ) {
      $value = "school";
    }
    else if(7 == $tag ) {
      $value = "star_half";
    }
    else if(8 == $tag ) {
      $value = "person_outline";
    }
    else if(9 == $tag ) {
      $value = "directions_run";
    }

    return $value;
  }

  function get_participant_class($class) {
    if(1 == $class) {
      $value = "Original Participant";
    }
    else if(2 == $class) {
      $value = "Replacement Prior";
    }
    else if(3 == $class) {
      $value = "Replacement During";
    }
    else if(4 == $class) {
      $value = "Eyeball Prior";
    }
    else if(5 == $class) {
      $value = "Eyeball During";
    }
    else if(6 == $class) {
      $value = "Replacement w/o Scorecard";
    }
    else if(20 == $class) {
      $value = "Counselor";
    }
    else if(21 == $class) {
      $value = "Counselor Replacement";
    }
    else if(22 == $class) {
      $value = "Counselor Replacement X";
    }

    return $value;
  }

  function get_h2h_week_range($week_letter) {
    if("a" == $week_letter) {
      $value = "1-4";
    }
    else if("b" == $week_letter) {
      $value = "5-8";
    }
    else if("c" == $week_letter) {
      $value = "9-12";
    }
    else if("d" == $week_letter) {
      $value = "13-16";
    }

    return $value;
  }

  function tick_checkbox($a, $b) {
  	if(strstr($a, $b))
  		return "checked";
  	else
  		return "";
  }

  function select_option($a, $b) {
    if($a == $b) {
      return "selected";
    }
    else {
      return "";
    }
  }

  function attendance_selected_hue($value) {
    if(0 == $value) {
      $value = "#50514f";
    }
    else if(1 == $value) {
      $value = "#247ba0";
    }
    else if(2 == $value) {
      $value = "#f25f5c";
    }
    else if(3 == $value) {
      $value = "#70c1b3";
    }
    else if(4 == $value) {
      $value = "#e0c041";
    }

    return $value;
  }

  function checkParticipantEntry($last_name,$first_name,$middle_name)
  {
  	global $db_connect;
  	$query = "SELECT *
  			 FROM list_transform_participant
  			 WHERE last_name ilike '%$last_name%' AND
  			 first_name ilike '%$first_name%' AND
  			 middle_name ilike '%$middle_name%'";

  	$result = pg_query($db_connect, $query);
  	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

  	return $row['id'];
  }

  function update_people_profile($participant_pk,$last_name,$first_name,$middle_name,$gender,$birthday,$contact_number,$class,$status,$notes,$username)
  {
  	global $db_connect;
  	$dt = new DateTime();
  	$timestamp = $dt->format('Y-m-d H:i:s');
  	$birthday = (!empty($birthday)) ? "'$birthday'" : "NULL";

  	echo $query = "UPDATE list_transform_participant
  					 SET last_name = '$last_name', first_name = '$first_name', middle_name = '$middle_name', contact_number = '$contact_number', updated_by = '$username', category = '$class', tag = '$status', notes = '$notes', gender = '$gender', birthday = $birthday, updated_date = TIMESTAMP '$timestamp'
  					 WHERE id = '$participant_pk'";

  	$result = pg_query($db_connect, $query);
  	if(!$result) {
  			echo "An error occurred.\n";
  			exit;
  	}
  }

  function get_application_details($application_pk) {
    global $db_connect;
  	$query = "SELECT *
  					FROM list_transform_application
  					WHERE id = '$application_pk'";
  	$result = pg_query($db_connect, $query);
  	return pg_fetch_array($result,NULL,PGSQL_BOTH);
  }

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

  function add_people_profile($participant_id,$last_name,$first_name,$middle_name,$application_pk,$username,$contact_number,$class,$gender,$birthday,$notes,$status) {
  	global $db_connect;
  	$dt = new DateTime();
  	$timestamp = $dt->format('Y-m-d H:i:s');
  	$birthday = !empty($birthday) ? "'$birthday'" : "NULL";

  	$query = "INSERT INTO list_transform_participant
  	 (participant_id,last_name,first_name,middle_name,fk_entry_id,created_date,created_by,contact_number,category,gender,birthday,notes,tag)
  	 VALUES
  	 ('$participant_id','$last_name','$first_name','$middle_name','$application_pk',TIMESTAMP '$timestamp','$username','$contact_number','$class','$gender',$birthday,'$notes','$status')";

  	$result = pg_query($db_connect, $query);
  	if (!$result)
  		{
  			echo "An error occurred.\n";
  			exit;
  		}
  }

  function getBIB_kit_name($id)	{
    global $db_connect;
  	$query = "SELECT *
  						FROM list_bib
  						WHERE id = $id
  						ORDER BY kit_name";

  	$result = pg_query($db_connect, $query);
    $bib = pg_fetch_array($result,NULL,PGSQL_BOTH);
  	return $bib['kit_name'];
  }

  function getBaseName($a) {
  	if($a == "1")
  	  $base = "Bacolod";
  	else if($a == "2")
  	  $base = "Bohol";
  	else if($a == "3")
  	  $base = "Dumaguete";
  	else if($a == "4")
  	  $base = "General Santos";
  	else if($a == "5")
  	  $base = "Koronadal";
  	else if($a == "6")
  	  $base = "Palawan";
  	else if($a == "7")
  	  $base = "Dipolog";
  	else if($a == "8")
  	  $base = "Iloilo";
  	else if($a == "9")
  	  $base = "Cebu";
  	else if($a == "10")
  	  $base = "Roxas";
  	else if($a == "99")
  	  $base = "Hong Kong";
  	else if($a == "98")
  	  $base = "Manila";
  	else
  	  $base = "Undefined";

  	return $base;
  }

  function get_transform_program_name($a) {
  	if($a == "1")
  	  $program = "Transform - Regular";
  	else if($a == "2")
  	  $program = "Transform - Jumpstart Parents";
  	else if($a == "3")
  	  $program = "Transform - OSY";
  	else if($a == "4")
  	  $program = "Transform - SLP";
  	else if($a == "5")
  	  $program = "Transform - PBSP";
  	else
  	  $program = "Undefined";

  	return $program;
  }
?>
