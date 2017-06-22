<?php
include_once "_functions-general.php";
//include_once "../_parentFunctions.php";

//identifies which command to execute
$command = (isset($_GET['command']) ? $_GET['command'] : "");
$dt = new DateTime();
$timestamp = $dt->format('Y-m-d H:i:s');

if("display_drop_down_communities" === $command) {
	$year = $_GET['year'];
	$year_short = substr($year,-2);
  $batch = $_GET['batch'];
	$base = $_GET['base'];
	$base = str_pad($base, 2, 0, STR_PAD_LEFT);
	$key = $year_short.$base."_".$batch."%";

	$query = "SELECT *
						FROM list_transform_application
						WHERE community_id ilike '$key'
						AND tag > 4
						ORDER BY community_id";
	$result = pg_query($db_connect, $query);

	echo "<option disabled selected>(Select One)</option>";

	while($community = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		$application_pk = $community['id'];
		$application_tag = $community['tag'];
		$community_id = $community['community_id'];
		$community_pastor = $community['pastor_last_name'].", ".$community['pastor_first_name'];
		$community_city = $community['application_city'];
		$community_barangay = $community['application_barangay'];
		$location_note = $community['location_note'];
		$location = ($location_note == "" ? "$community_barangay, $community_city" : "$community_barangay, $location_note");

		echo "<option value='$application_pk'>$community_id - Pastor $community_pastor - $location</option>";
	}
}

else if("update_person_attendance_instance" === $command) {
	$participant_pk = $_GET['participant_pk'];
	$column = $_GET['column'];
	$value = $_GET['value'];
	$username = $_GET['username'];

	$query = "UPDATE list_transform_attendance_participant SET $column = '$value', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE fk_participant_pk = '$participant_pk'";

	$result = pg_query($db_connect, $query);
}

else if("update_pastor_attendance_instance" === $command) {
	$pastor_pk = $_GET['participant_pk'];
	$column = $_GET['column'];
	$value = $_GET['value'];
	$username = $_GET['username'];

	echo $query = "UPDATE list_transform_attendance_pastor SET $column = '$value', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE fk_pastor_pk = '$pastor_pk'";

	$result = pg_query($db_connect, $query);
}

else if("update_h2h_instance" === $command) {
	echo "<br/>pk ".$participant_pk = $_GET['participant_pk'];
	echo "<br/>wk ".$h2h_week = $_GET['week_letter'];
	echo "<br/>val ".$value = $_GET['value'];
	$username = $_GET['username'];

	$query = "SELECT variable_7
						FROM list_transform_attendance_participant
						WHERE fk_participant_pk = '$participant_pk'";

	$result = pg_query($db_connect, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	echo "<br/>set".$h2h_set = $row['variable_7'];
	echo "<br/>";
	//there
	if(false !== strpos($h2h_set, $h2h_week)) {
		if("false" == $value) {
			echo $query = "UPDATE list_transform_attendance_participant
								SET variable_7 = REPLACE(variable_7, '$h2h_week', '')
								WHERE fk_participant_pk = '$participant_pk'";
		}
		else {
			echo "x";
		}
	}
	else {
		if("true" == $value) {
			echo $query = "UPDATE list_transform_attendance_participant
										 SET variable_7 =
										 	CASE WHEN variable_7 IS NULL THEN '$h2h_week'
										 	ELSE variable_7 || '$h2h_week'
											END
										 WHERE fk_participant_pk = '$participant_pk'";
		}
		else {
			echo "y";
		}
	}

	/*$query = "UPDATE list_transform_attendance_participant SET $column = '$value', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE fk_participant_pk = '$participant_pk'";
*/
	$result = pg_query($db_connect, $query);
}

else if("update_participant" === $command) {
	$participant_pk = $_GET['participant_pk'];
	$last_name = $_GET['last_name'];
	$first_name = $_GET['first_name'];
	$middle_name = $_GET['middle_name'];
	$gender = $_GET['gender'];
	$birthday = $_GET['birthday'];
	$contact_number = $_GET['contact_number'];
	$class = $_GET['people_class'];
	$status = $_GET['status'];
	$notes = $_GET['notes'];
	$username = $_GET['username'];

	if(checkParticipantEntry($last_name,$first_name,$middle_name)!= "" && checkParticipantEntry($last_name,$first_name,$middle_name) != $participant_pk)
		echo $notice = "Sorry but an entry with this name already exists.";

	else {
		update_people_profile($participant_pk,$last_name,$first_name,$middle_name,$gender,$birthday,$contact_number,$class,$status,$notes,$username);

		echo "You have successfully updated this profile.";
	}
}

else if("add_participant" === $command) {
	$application_pk = $_GET['application_pk'];
	$last_name = $_GET['last_name'];
	$first_name = $_GET['first_name'];
	$middle_name = $_GET['middle_name'];
	$gender = $_GET['gender'];
	$birthday = $_GET['birthday'];
	$contact_number = $_GET['contact_number'];
	$class = $_GET['people_class'];
	$status = $_GET['status'];
	$notes = $_GET['notes'];
	$username = $_GET['username'];
	$application = get_application_details($application_pk);
	$participant_id = generate_participant_id($application_pk,$application['community_id']);

	if(checkParticipantEntry($last_name,$first_name,$middle_name) != "")
		echo "Sorry but an entry with this name already exists. (ง •̀_•́)ง ผ(•̀_•́ผ)";

	else {
		add_people_profile($participant_id,$last_name,$first_name,$middle_name,$application_pk,$username,$contact_number,$class,$gender,$birthday,$notes,$status);

		echo "You have successfully added this profile. ヽ(´▽｀)ノ";
	}
}

else if("update_weekly_instance" === $command) {
	$application_pk = $_GET['application_pk'];
	$week = $_GET['week'];
	$value = $_GET['value'];
	$column = $_GET['column'];
	$username = $_GET['username'];

	if("false" == $value) {
		$value = 0;
	}
	else if("true" == $value) {
		$value = 1;
	}

	//check existence in database
	$query = "SELECT *
					 	FROM log_transform_weekly
					 	WHERE fk_application_pk = '$application_pk'
					 	AND week_number = '$week'";

	$result = pg_query($db_connect, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	$i = $row['0'];

	if($i == "") {
		$query = "INSERT INTO log_transform_weekly
		 (week_number,
			fk_application_pk,
			$column,
			updated_by,
			updated_date)

		 VALUES
		 ('$week',
			'$application_pk',
			'$value',
			'$username',
			TIMESTAMP '$timestamp')";
	}
	else {
		$query = "UPDATE log_transform_weekly
							SET $column = '$value', updated_by = '$username', updated_date = TIMESTAMP '$timestamp'
							WHERE fk_application_pk = '$application_pk'
							AND week_number = '$week'";
	}

	$result = pg_query($db_connect, $query);
	//echo "You have successfully added this profile. ヽ(´▽｀)ノ";
}

else if("gender_reports" === $command) {
	$year = $_GET['year'];
	$batch = $_GET['batch'];

	$male_total = 0;
  $female_total = 0;
  $undefined_total = 0;
  $m_pastor = 0;
  $f_pastor = 0;
  $u_pastor = 0;
  $m_counselor = 0;
  $f_counselor = 0;
  $u_counselor = 0;
  $m_participant = 0;
  $f_participant = 0;
  $u_particiapnt = 0;

  function count_gender_total($class,$year,$batch,$gender,$base_id) {
    global $db_connect;
    $year = $year;
  	$year_short = substr($year,-2);
    $batch = $batch;
  	$base = str_pad($base_id, 2, 0, STR_PAD_LEFT);
  	$key = $year_short.$base."_".$batch."%";
    $extra = "";
    if($gender == '')
      $gender_specify = "gender is null";
    else {
      $gender_specify = "gender = '$gender'";
    }

    if("pastor" == $class) {
      $table = "list_pastor";
      $with = "list_pastor.id";
      $pair = "pastor_id";
      $extra = "";
    }
    else if("participant" == $class) {
      $table = "list_transform_participant";
      $with = "list_transform_participant.fk_entry_id";
      $pair = "id";
      $extra = "AND
                	(category = '1' or category = '2' or category = '3' or category = '4' or category = '5' or category = '6')
                AND
                	(list_transform_participant.tag = '5' or list_transform_participant.tag = '6' or list_transform_participant.tag = '9')";
    }
    else if("counselor" == $class) {
      $table = "list_transform_participant";
      $with = "list_transform_participant.fk_entry_id";
      $pair = "id";
      $extra = "AND
                	(category = '20' or category = '21' or category = '22')
                AND
                	(list_transform_participant.tag = '5' or list_transform_participant.tag = '6' or list_transform_participant.tag = '9')";
    }

    $query = "SELECT count(*)
              FROM list_transform_application
              LEFT JOIN $table
              ON list_transform_application.$pair = $with
              WHERE community_id ilike '$key'
              $extra
              AND $gender_specify";
    $result = pg_query($db_connect, $query);
    $row = pg_fetch_array($result,NULL,PGSQL_BOTH);
    return $row['count'];
  }

	echo "<table class='highlight'>
	  <tr>
	    <th width='15%'></th>
	    <th width='5%'></th>
	    <th class='numeric' width='20%'>Pastor</th>
	    <th class='numeric' width='20%'>Counselor</th>
	    <th class='numeric' width='20%'>Participant</th>
	    <th class='numeric' width='20%'>Total</th>
	  </tr>";

	for($i=1;$i<11;$i++) {
	    $a = count_gender_total('pastor', $year, $batch,'Male',$i);
	    $b = count_gender_total('counselor', $year, $batch,'Male',$i);
	    $c = count_gender_total('participant', $year, $batch,'Male',$i);
	    $d = count_gender_total('pastor',$year, $batch,'Female',$i);
	    $e = count_gender_total('counselor',$year, $batch,'Female',$i);
	    $f = count_gender_total('participant',$year, $batch,'Female',$i);
	    $g = count_gender_total('pastor',$year, $batch,'',$i);
	    $h = count_gender_total('counselor',$year, $batch,'',$i);
	    $j = count_gender_total('participant',$year, $batch,'',$i);
	    $male_total = $male_total + $a + $b + $c;
	    $female_total = $female_total + $d + $e + $f;
	    $undefined_total = $undefined_total + $g + $h + $j;

	    $m_pastor = $m_pastor + $a;
	    $f_pastor = $f_pastor + $d;
	    $u_pastor = $u_pastor + $g;
	    $m_counselor = $m_counselor + $b;
	    $f_counselor = $f_counselor + $e;
	    $u_counselor = $u_counselor + $h;
	    $m_participant = $m_participant + $c;
	    $f_participant = $f_participant + $f;
	    $u_particiapnt = $u_particiapnt + $j;

	    echo "
	      <tr>
	        <td></td>
	        <td>
	          <i class='material-icons' title='Male' style='color:#345972'>accessibility</i>
	        </td>
	        <td class='numeric'>$a</td>
	        <td class='numeric'>$b</td>
	        <td class='numeric'>$c</td>
	        <td class='numeric'>$male_total</td>
	      </tr>
	      <tr>
	        <td>".getBaseName($i)."</td>
	        <td>
	        <i class='material-icons' title='Female' style='color:#ff80aa'>accessibility</i>
	        </td>
	        <td class='numeric'>$d</td>
	        <td class='numeric'>$e</td>
	        <td class='numeric'>$f</td>
	        <td class='numeric'>$female_total</td>
	      </tr>
	      <tr style='border-bottom: dashed 2px rgba(166,166,166, 0.8);'>
	        <td></td>
	        <td>
	        <i class='material-icons' title='Undefined' style='color:#6d6d6d'>accessibility</i>
	        </td>
	        <td class='numeric'>$g</td>
	        <td class='numeric'>$h</td>
	        <td class='numeric'>$j</td>
	        <td class='numeric'>$undefined_total</td>
	      </tr>";

	      $male_total = 0;
	      $female_total = 0;
	      $undefined_total = 0;
	}
	echo "
	</table>
	<br/>
	<h1>TOTALS</h1>
	<div>
	  <span>Pastor:</span>
	  <strong style='color:#345972' title='Male'>$m_pastor</strong>,
	  <strong style='color:#ff80aa' title='Female'>$f_pastor</strong>,
	  <strong style='color:#6d6d6d' title='Undefined'>$u_pastor</strong>
	</div>
	<div>
	  <span>Counselor:</span>
	  <strong style='color:#345972' title='Male'>$m_counselor</strong>,
	  <strong style='color:#ff80aa' title='Female'>$f_counselor</strong>,
	  <strong style='color:#6d6d6d' title='Undefined'>$u_counselor</strong>
	</div>
	<div>
	  <span>Participant:</span>
	  <strong style='color:#345972' title='Male'>$m_participant</strong>,
	  <strong style='color:#ff80aa' title='Female'>$f_participant</strong>,
	  <strong style='color:#6d6d6d' title='Undefined'>$u_particiapnt</strong>
	</div>";
}
?>
