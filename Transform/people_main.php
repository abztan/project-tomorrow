<?php
  include_once "_functions-general.php";
  include_once "_controller-transform.php";

  $application_pk = $_GET['application_pk'];
	$sort = $_GET['sort'];

	if("default" == $sort) {
		$sort_seq = "participant_id ASC";
	}
	else if("name" == $sort) {
		$sort_seq = "last_name, first_name ASC";
	}
	else if("id" == $sort) {
		$sort_seq = "participant_id ASC";
	}
	else if("class" == $sort) {
		$sort_seq = "category ASC";
	}

	$query = "SELECT *
						FROM list_transform_participant
						WHERE fk_entry_id = '$application_pk'
						AND participant_id <> ''
						AND tag > '3'
						ORDER BY $sort_seq;";
	$result = pg_query($db_connect, $query);



	$i = 1;
	while($participant = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		$participant_pk = $participant['id'];
		$participant_id = $participant['participant_id'];
		$last_name = $participant['last_name'];
		$first_name = $participant['first_name'];
		$middle_name = $participant['middle_name'];
		$gender = $participant['gender'];
		$gender_hue = ("" == $gender ? "#6d6d6d" : ("Male" === $gender ? "#345972" : "#ff80aa"));
		$class = $participant['category'];
		$tag = $participant['tag'];
		$status = get_participant_status($tag);
		$status_icon = get_participant_status_icon($tag);
		$class_value = get_participant_class($class);

		echo "
					<tr>
						<td class='text'>$i. <span title='Last Name'>$last_name</span>, <span title='First Name'>$first_name</span> <span title='Middle Name'>$middle_name</span></td>
						<td class='text'><span title='Key ID: $participant_pk'>$participant_id</span></td>
						<td><span>$class_value</span></td>
						<td>
							<i class='material-icons' title='$gender' style='color:$gender_hue'>accessibility</i>&nbsp;&nbsp;
							<i class='material-icons' title='$status' style='color:#6d6d6d;'>$status_icon</i>
						</td>
						<td><i class='material-icons live' onclick='display_people_view(\"$participant_pk\");'>navigate_next</i></td>
					</tr>
		";

		$i++;
	}

?>
