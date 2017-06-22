  <?php
  include_once "_functions-general.php";
  include_once "_controller-transform.php";
  include_once "../user_session.php";
  global $username;

  $application_pk = $_GET['application_pk'];

	//ORIGINAL PARTICIPANTS TABLE
	//
	//
	//
	//
	//

	$query = "SELECT *
						FROM list_transform_participant
						WHERE fk_entry_id = '$application_pk'
						AND (category = '1' OR category = '2')
						AND (tag = '5' OR tag = '6')
						ORDER BY participant_id";
	$result = pg_query($db_connect, $query);

	echo "
				<table class='highlight'>
				<thead>
					<tr>
						<th></th>
						<th class='sub'>H2H<br/>1-4</th>
						<th class='sub'>H2H<br/>5-8</th>
						<th class='sub'>H2H<br/>9-12</th>
						<th class='sub'>H2H<br/>13-16</th>
						<th class='main' colspan='16'>Participants</th>
					</tr>
				</thead>
				<tbody>";

	while ($participant = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		$participant_pk = $participant['id'];
		$last_name = $participant['last_name'];
		$first_name = $participant['first_name'];
		$middle_initial = substr($participant['middle_name'],0,1);
		$participant_id = $participant['participant_id'];
		$full_name = $last_name.", ".$first_name." ".$middle_initial;

		//generates attendance row if participant does not exist, else it captures the attendance primary key
		$result_x = pg_query($db_connect, "SELECT id FROM list_transform_attendance_participant WHERE fk_participant_pk = '$participant_pk'");
		$row_x = pg_fetch_array($result_x,NULL,PGSQL_BOTH);
		$attendance_pk = $row_x['id'];
		if("" == $attendance_pk) {
			$query_y = "INSERT INTO list_transform_attendance_participant
			 (fk_application_pk,fk_participant_pk,updated_date,updated_by)
			 VALUES
			 ($application_pk,$participant_pk,TIMESTAMP '$timestamp','$username')";
			$result_y = pg_query($db_connect, $query_y);
			if (!$result_y) {
				echo "An error occurred.\n";
				exit;
			}
		}

		echo "
					<tr>
						<td class='text'>$full_name<p style='color:#e05b4a' title='KEY: $participant_pk'>".substr($participant_id,0,8)."<strong>".substr($participant_id,-2)."</strong></p>";

		//Main person attendance query
		$query_1 = "SELECT *
								FROM list_transform_attendance_participant
								WHERE fk_participant_pk = '$participant_pk'";
		$result_1 = pg_query($db_connect, $query_1);
		$attendance = pg_fetch_array($result_1,NULL,PGSQL_BOTH);

		//H2H stuff here... so sleep deprived...
		for($i="a";$i<"e";$i++) {
			$identity = $participant_pk.$i;
			$x = "h2h_$identity";
			$y = "div_h2h_$identity";
			$default = $attendance['variable_7'];

			echo "
						<td>
							<input title='H2H ".get_h2h_week_range($i).": ($participant_id) $full_name' type='checkbox' id='$x' ".tick_checkbox($attendance['variable_7'], $i)." value='$i' onchange='display_h2h_update(\"$identity\")'/>
							<div hidden id='$y'>
								<span class='material-icons' style='cursor:pointer; color:#e05b4a;' onclick='update_h2h_attendance($participant_pk,\"$identity\",1,\"$i\",\"$username\")'>check</span>
							</div>
						</td>";
		}

		//Attendance stuff here... more chicken noise...
		for($i=1;$i<17;$i++) {
			$identity = $participant_pk.$i;
			$x = "att_".$identity;
			$y = "div_att".$identity;
			$column = "week_".$i;
			$default = $attendance[$column];
			$default = ($default == "" ? 0 : $default);

			echo "
						<td>
							<select class='attendance' id='$x' style='color:".attendance_selected_hue($default).";' title='($participant_id) $full_name' onchange='display_attendance_update($identity)'>
								<option class='a_op1' value='0' ".($default == 0 ? "selected" : "").">WK $i</option>
								<option class='a_op2' value='1' ".($default == 1 ? "selected" : "").">X</option>
								<option class='a_op3' value='2' ".($default == 2 ? "selected" : "").">A</option>
								<option class='a_op4' value='3' ".($default == 3 ? "selected" : "").">F</option>
								<option class='a_op5' value='4' ".($default == 4 ? "selected" : "").">O</option>
							</select>
							<div id='$y' hidden>
								<span class='material-icons' style='cursor:pointer; color:#e05b4a;' onclick='update_people_attendance(\"update_person_attendance_instance\",$participant_pk,$identity,1,$i,$default,\"$username\");display_attendance_main();'>check</span>
								<span hidden onclick='update_people_attendance(\"update_person_attendance_instance\",$participant_pk,$identity,0,$i,$default,\"$username\")'>x</span>
							</div>
						</td>";
		}
	}

  echo "
				</tbody>
				</table><br/><br/><br/>
			 ";

  //PASTOR
 	//
 	//
 	//
 	//
 	//

	$query = "SELECT *
						FROM list_transform_application
						WHERE id = '$application_pk'";
	$result = pg_query($db_connect, $query);

   echo "
 				<table class='highlight'>
 				<thead>
 					<tr>
 						<th></th>
 						<th class='sub'>H2H<br/>1-4</th>
 						<th class='sub'>H2H<br/>5-8</th>
 						<th class='sub'>H2H<br/>9-12</th>
 						<th class='sub'>H2H<br/>13-16</th>
 						<th class='main' colspan='16'>Pastor & Counselors</th>
 					</tr>
 				</thead>
 				<tbody>";

 	while ($participant = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
 		$pastor_pk = $participant['id'];
 		$last_name = $participant['pastor_last_name'];
 		$first_name = $participant['pastor_first_name'];
 		$middle_initial = substr($participant['pastor_middle_initial'],0,1);
    $pastor_id = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
 		$full_name = $last_name.", ".$first_name." ".$middle_initial;

 		//generates attendance row if participant does not exist, else it captures the attendance primary key
 		$result_x = pg_query($db_connect, "SELECT id FROM list_transform_attendance_pastor WHERE fk_pastor_pk = '$pastor_pk'");
 		$row_x = pg_fetch_array($result_x,NULL,PGSQL_BOTH);
 		$attendance_pk = $row_x['id'];
 		if("" == $attendance_pk) {
 			$query_y = "INSERT INTO list_transform_attendance_pastor
 			 (fk_application_pk,fk_pastor_pk,updated_date,updated_by)
 			 VALUES
 			 ($application_pk,$pastor_pk,TIMESTAMP '$timestamp','$username')";
 			$result_y = pg_query($db_connect, $query_y);
 			if (!$result_y) {
 				echo "An error occurred.\n";
 				exit;
 			}
 		}

 		echo "
 					<tr>
 						<td class='text'>$full_name<p style='color:#e05b4a' title='KEY: $pastor_pk'>$pastor_id</p>";

 		//Main person attendance query
 		$query_1 = "SELECT *
 								FROM list_transform_attendance_pastor
 								WHERE fk_pastor_pk = '$pastor_pk'";
 		$result_1 = pg_query($db_connect, $query_1);
 		$attendance = pg_fetch_array($result_1,NULL,PGSQL_BOTH);

 		//H2H stuff here.
 		for($i="a";$i<"e";$i++) {
 			$identity = $pastor_pk.$i;
 			$x = "h2h_$identity";
 			$y = "div_h2h_$identity";
 			$default = $attendance['variable_7'];

 			echo "
 						<td>
 							<input title='H2H ".get_h2h_week_range($i).": ($pastor_id) $full_name' type='checkbox' id='$x' ".tick_checkbox($attendance['variable_7'], $i)." value='$i' onchange='display_h2h_update(\"$identity\")'/>
 							<div hidden id='$y'>
 								<span class='material-icons' style='cursor:pointer; color:#e05b4a;' onclick='update_h2h_attendance($pastor_pk,\"$identity\",1,\"$i\",\"$username\")'>check</span>
 							</div>
 						</td>";
 		}

 		//Attendance stuff here... more chicken noise...
 		for($i=1;$i<17;$i++) {
 			$identity = $pastor_pk.$i;
 			$x = "att_".$identity;
 			$y = "div_att".$identity;
 			$column = "week_".$i;
 			$default = $attendance[$column];
 			$default = ($default == "" ? 0 : $default);

 			echo "
 						<td>
 							<select class='attendance' id='$x' style='color:".attendance_selected_hue($default).";' title='($pastor_id) $full_name' onchange='display_attendance_update($identity)'>
 								<option class='a_op1' value='0' ".($default == 0 ? "selected" : "").">WK $i</option>
 								<option class='a_op2' value='1' ".($default == 1 ? "selected" : "").">X</option>
 								<option class='a_op3' value='2' ".($default == 2 ? "selected" : "").">A</option>
 							</select>
 							<div id='$y' hidden>
 								<span class='material-icons' style='cursor:pointer; color:#e05b4a;' onclick='update_people_attendance(\"update_pastor_attendance_instance\",$pastor_pk,$identity,1,$i,$default,\"$username\");display_attendance_main();'>check</span>
 								<span hidden onclick='update_people_attendance(\"update_pastor_attendance_instance\",$pastor_pk,$identity,0,$i,$default,\"$username\")'>x</span>
 							</div>
 						</td>";
 		}
 	}

	//COUNSELOR TABLE
	//
	//
	//
	//
	//

 	$query = "SELECT *
 						FROM list_transform_participant
 						WHERE fk_entry_id = '$application_pk'
 						AND (category = '20' OR category = '21')
 						AND (tag = '5' OR tag = '6')
 						ORDER BY participant_id";
 	$result = pg_query($db_connect, $query);
	while ($participant = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		$participant_pk = $participant['id'];
		$last_name = $participant['last_name'];
		$first_name = $participant['first_name'];
		$middle_initial = substr($participant['middle_name'],0,1);
		$participant_id = $participant['participant_id'];
		$full_name = $last_name.", ".$first_name." ".$middle_initial;

		//generates attendance row if participant does not exist, else it captures the attendance primary key
		$result_x = pg_query($db_connect, "SELECT id FROM list_transform_attendance_participant WHERE fk_participant_pk = '$participant_pk'");
		$row_x = pg_fetch_array($result_x,NULL,PGSQL_BOTH);
		$attendance_pk = $row_x['id'];
		if("" == $attendance_pk) {
			$query_y = "INSERT INTO list_transform_attendance_participant
			 (fk_application_pk,fk_participant_pk,updated_date,updated_by)
			 VALUES
			 ($application_pk,$participant_pk,TIMESTAMP '$timestamp','$username')";
			$result_y = pg_query($db_connect, $query_y);
			if (!$result_y) {
				echo "An error occurred.\n";
				exit;
			}
		}

		echo "
					<tr>
						<td class='text'>$full_name<p style='color:#e05b4a' title='KEY: $participant_pk'>".substr($participant_id,0,8)."<strong>".substr($participant_id,-2)."</strong></p>";

		//Main person attendance query
		$query_1 = "SELECT *
								FROM list_transform_attendance_participant
								WHERE fk_participant_pk = '$participant_pk'";
		$result_1 = pg_query($db_connect, $query_1);
		$attendance = pg_fetch_array($result_1,NULL,PGSQL_BOTH);

		//H2H stuff here... so sleep deprived...
		for($i="a";$i<"e";$i++) {
			$identity = $participant_pk.$i;
			$x = "h2h_$identity";
			$y = "div_h2h_$identity";
			$default = $attendance['variable_7'];

			echo "
						<td>
							<input title='H2H ".get_h2h_week_range($i).": ($participant_id) $full_name' type='checkbox' id='$x' ".tick_checkbox($attendance['variable_7'], $i)." value='$i' onchange='display_h2h_update(\"$identity\")'/>
							<div hidden id='$y'>
								<span class='material-icons' style='cursor:pointer; color:#e05b4a;' onclick='update_h2h_attendance($participant_pk,\"$identity\",1,\"$i\",\"$username\")'>check</span>
							</div>
						</td>";
		}

		//Attendance stuff here... more chicken noise...
		for($i=1;$i<17;$i++) {
			$identity = $participant_pk.$i;
			$x = "att_".$identity;
			$y = "div_att".$identity;
			$column = "week_".$i;
			$default = $attendance[$column];
			$default = ($default == "" ? 0 : $default);

			echo "
						<td>
							<select class='attendance' id='$x' style='color:".attendance_selected_hue($default).";' title='($participant_id) $full_name' onchange='display_attendance_update($identity)'>
								<option class='a_op1' value='0' ".($default == 0 ? "selected" : "").">WK $i</option>
								<option class='a_op2' value='1' ".($default == 1 ? "selected" : "").">X</option>
								<option class='a_op3' value='2' ".($default == 2 ? "selected" : "").">A</option>
							</select>
							<div id='$y' hidden>
								<span class='material-icons' style='cursor:pointer; color:#e05b4a;' onclick='update_people_attendance(\"update_person_attendance_instance\",$participant_pk,$identity,1,$i,$default,\"$username\");display_attendance_main();'>check</span>
								<span hidden onclick='update_people_attendance(\"update_person_attendance_instance\",$participant_pk,$identity,0,$i,$default,\"$username\")'>x</span>
							</div>
						</td>";
		}
	}

  echo "
				</tbody>
				</table><br/><br/><br/>
			 ";

	//REPLACEMENTS TABLE
	//
	//
	//
	//
	//

 	$query = "SELECT *
 						FROM list_transform_participant
 						WHERE fk_entry_id = '$application_pk'
 						AND (category = '2' OR category = '3' OR category = '4' OR category = '5' OR category = '6')
 						AND (tag = '5' OR tag = '6')
 						ORDER BY participant_id";
 	$result = pg_query($db_connect, $query);

  echo "
				<table class='highlight'>
				<thead>
					<tr>
						<th></th>
						<th class='sub'>H2H<br/>1-4</th>
						<th class='sub'>H2H<br/>5-8</th>
						<th class='sub'>H2H<br/>9-12</th>
						<th class='sub'>H2H<br/>13-16</th>
						<th class='main' colspan='16'>Participant Replacements</th>
					</tr>
				</thead>
				<tbody>";

	while ($participant = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		$participant_pk = $participant['id'];
		$last_name = $participant['last_name'];
		$first_name = $participant['first_name'];
		$middle_initial = substr($participant['middle_name'],0,1);
		$participant_id = $participant['participant_id'];
		$full_name = $last_name.", ".$first_name." ".$middle_initial;

		//generates attendance row if participant does not exist, else it captures the attendance primary key
		$result_x = pg_query($db_connect, "SELECT id FROM list_transform_attendance_participant WHERE fk_participant_pk = '$participant_pk'");
		$row_x = pg_fetch_array($result_x,NULL,PGSQL_BOTH);
		$attendance_pk = $row_x['id'];
		if("" == $attendance_pk) {
			$query_y = "INSERT INTO list_transform_attendance_participant
			 (fk_application_pk,fk_participant_pk,updated_date,updated_by)
			 VALUES
			 ($application_pk,$participant_pk,TIMESTAMP '$timestamp','$username')";
			$result_y = pg_query($db_connect, $query_y);
			if (!$result_y) {
				echo "An error occurred.\n";
				exit;
			}
		}

		echo "
					<tr>
						<td class='text'>$full_name<p style='color:#e05b4a' title='KEY: $participant_pk'>".substr($participant_id,0,8)."<strong>".substr($participant_id,-2)."</strong></p>";

		//Main person attendance query
		$query_1 = "SELECT *
								FROM list_transform_attendance_participant
								WHERE fk_participant_pk = '$participant_pk'";
		$result_1 = pg_query($db_connect, $query_1);
		$attendance = pg_fetch_array($result_1,NULL,PGSQL_BOTH);

		//H2H stuff here... so sleep deprived...
		for($i="a";$i<"e";$i++) {
			$identity = $participant_pk.$i;
			$x = "h2h_$identity";
			$y = "div_h2h_$identity";
			$default = $attendance['variable_7'];

			echo "
						<td>
							<input title='H2H ".get_h2h_week_range($i).": ($participant_id) $full_name' type='checkbox' id='$x' ".tick_checkbox($attendance['variable_7'], $i)." value='$i' onchange='display_h2h_update(\"$identity\")'/>
							<div hidden id='$y'>
								<span class='material-icons' style='cursor:pointer; color:#e05b4a;' onclick='update_h2h_attendance($participant_pk,\"$identity\",1,\"$i\",\"$username\")'>check</span>
							</div>
						</td>";
		}

		//Attendance stuff here... more chicken noise...
		for($i=1;$i<17;$i++) {
			$identity = $participant_pk.$i;
			$x = "att_".$identity;
			$y = "div_att".$identity;
			$column = "week_".$i;
			$default = $attendance[$column];
			$default = ($default == "" ? 0 : $default);

			echo "
						<td>
							<select class='attendance' id='$x' style='color:".attendance_selected_hue($default).";' title='($participant_id) $full_name' onchange='display_attendance_update($identity)'>
								<option class='a_op1' value='0' ".($default == 0 ? "selected" : "").">WK $i</option>
								<option class='a_op2' value='1' ".($default == 1 ? "selected" : "").">X</option>
								<option class='a_op3' value='2' ".($default == 2 ? "selected" : "").">A</option>
								<option class='a_op4' value='3' ".($default == 3 ? "selected" : "").">F</option>
								<option class='a_op5' value='4' ".($default == 4 ? "selected" : "").">O</option>
							</select>
							<div id='$y' hidden>
								<span class='material-icons' style='cursor:pointer; color:#e05b4a;' onclick='update_people_attendance(\"update_person_attendance_instance\",$participant_pk,$identity,1,$i,$default,\"$username\");display_attendance_main();'>check</span>
								<span hidden onclick='update_people_attendance(\"update_person_attendance_instance\",$participant_pk,$identity,0,$i,$default,\"$username\")'>x</span>
							</div>
						</td>";
		}
	}

  echo "
				</tbody>
				</table>
			 ";

?>
