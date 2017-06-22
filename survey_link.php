<?php

  $connect_string_pt = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
  $db_connect_pt = pg_connect($connect_string_pt) or die("Can't connect to database".pg_last_error());

  global $connect_string_pt;
  global $db_connect_pt;

  $conn_string_odk = "host=icmdb.cfewawr1rnp0.ap-southeast-1.rds.amazonaws.com port=5432 dbname=ProjectTomorrow user=icmadmin password=password connect_timeout=5";
  $db_connect_odk = pg_connect($conn_string_odk) or die("Can't connect to database".pg_last_error());
  global $conn_string_odk;
  global $db_connect_odk;


  $base1_count = 0;
  $base2_count = 0;
  $base3_count = 0;
  $base4_count = 0;
  $base5_count = 0;
  $base6_count = 0;
  $base7_count = 0;
  $base8_count = 0;
  $base9_count = 0;
  $base10_count = 0;
  $survey_base1_count = 0;
  $survey_base2_count = 0;
  $survey_base3_count = 0;
  $survey_base4_count = 0;
  $survey_base5_count = 0;
  $survey_base6_count = 0;
  $survey_base7_count = 0;
  $survey_base8_count = 0;
  $survey_base9_count = 0;
  $survey_base10_count = 0;
  $last_name_1 = 0;
  $last_name_2 = 0;
  $last_name_3 = 0;
  $last_name_4 = 0;
  $last_name_5 = 0;
  $last_name_6 = 0;
  $last_name_7 = 0;
  $last_name_8 = 0;
  $last_name_9 = 0;
  $last_name_10 = 0;

  $query_1 = 'SELECT "PARTICIPANT_ID_VERIFY","CST_SEC_A_LNAME1","_URI"
            from "PRE_16_B2_V3_1B_R2_CORE"
            ORDER BY "PARTICIPANT_ID_VERIFY"';
  $request_1 = pg_query($db_connect_odk,$query_1);
  while ($array_1 = pg_fetch_array($request_1,NULL,PGSQL_BOTH)) {
    $survey_participant_id = $array_1['PARTICIPANT_ID_VERIFY'];
    $survey_last_name = $array_1['CST_SEC_A_LNAME1'];
    $survey_pk = $array_1['_URI'];
    $survey_base = substr($survey_participant_id,2,2);

    if($survey_base == "01") {
      $survey_base1_count++;
    }
    else if($survey_base == "02") {
      $survey_base2_count++;
    }
    else if($survey_base == "03") {
      $survey_base3_count++;
    }
    else if($survey_base == "04") {
      $survey_base4_count++;
    }
    else if($survey_base == "05") {
      $survey_base5_count++;
    }
    else if($survey_base == "06") {
      $survey_base6_count++;
    }
    else if($survey_base == "07") {
      $survey_base7_count++;
    }
    else if($survey_base == "08") {
      $survey_base8_count++;
    }
    else if($survey_base == "09") {
      $survey_base9_count++;
    }
    else if($survey_base == 10) {
      $survey_base10_count++;
    }

      $query_2 = pg_query($db_connect_pt,"SELECT base_id, list_transform_participant.last_name FROM list_transform_participant LEFT JOIN list_transform_application ON list_transform_participant.fk_entry_id = list_transform_application.id WHERE participant_id = '$survey_participant_id'");
      while ($array_2 = pg_fetch_array($query_2,NULL,PGSQL_BOTH)) {
        $base_id = $array_2['base_id'];
        $p_last_name = $array_2['last_name'];

        if($base_id == 1) {
          $base1_count++;
          if(strtolower(trim($p_last_name)) == strtolower(trim($survey_last_name)))
            $last_name_1++;
        }
        else if($base_id == 2) {
          $base2_count++;
          if(strtolower(trim($p_last_name)) == strtolower(trim($survey_last_name)))
            $last_name_2++;
        }
        else if($base_id == 3) {
          $base3_count++;
          if(strtolower(trim($p_last_name)) == strtolower(trim($survey_last_name)))
            $last_name_3++;
        }
        else if($base_id == 4) {
          $base4_count++;
          if(strtolower(trim($p_last_name)) == strtolower(trim($survey_last_name)))
            $last_name_4++;
        }
        else if($base_id == 5) {
          $base5_count++;
          if(strtolower(trim($p_last_name)) == strtolower(trim($survey_last_name)))
            $last_name_5++;
        }
        else if($base_id == 6) {
          $base6_count++;
          if(strtolower(trim($p_last_name)) == strtolower(trim($survey_last_name)))
            $last_name_6++;
        }
        else if($base_id == 7) {
          $base7_count++;
          if(strtolower(trim($p_last_name)) == strtolower(trim($survey_last_name)))
            $last_name_7++;
        }
        else if($base_id == 8) {
          $base8_count++;
          if(strtolower(trim($p_last_name)) == strtolower(trim($survey_last_name)))
            $last_name_8++;
        }
        else if($base_id == 9) {
          $base9_count++;
          if(strtolower(trim($p_last_name)) == strtolower(trim($survey_last_name)))
            $last_name_9++;
        }
        else if($base_id == 10) {
          $base10_count++;
          if(strtolower(trim($p_last_name)) == strtolower(trim($survey_last_name)))
            $last_name_10++;
        }
      }
  }

?>

  <table>
    <tr>
      <td>Bacolod</td>
      <td><?= $base1_count ." | ". $last_name_1 ." / ". $survey_base1_count?></td>
    </tr>

    <tr>
      <td>Bohol</td>
      <td><?= $base2_count ." | ". $last_name_2 ." / ".$survey_base2_count?></td>
    </tr>

    <tr>
      <td>Dumaguete</td>
      <td><?= $base3_count ." | ". $last_name_3 ." / ".$survey_base3_count?></td>
    </tr>

    <tr>
      <td>Gensan</td>
      <td><?= $base4_count ." | ". $last_name_4 ." / ".$survey_base4_count?></td>
    </tr>

    <tr>
      <td>Koronadal</td>
      <td><?= $base5_count ." | ". $last_name_5 ." / ".$survey_base5_count?></td>
    </tr>

    <tr>
      <td>Palawan</td>
      <td><?= $base6_count ." | ". $last_name_6 ." / ".$survey_base6_count?></td>
    </tr>

    <tr>
      <td>Dipolog</td>
      <td><?= $base7_count ." | ". $last_name_7 ." / ".$survey_base7_count?></td>
    </tr>

    <tr>
      <td>Iloilo</td>
      <td><?= $base8_count ." | ". $last_name_8 ." / ".$survey_base8_count?></td>
    </tr>

    <tr>
      <td>Cebu</td>
      <td><?= $base9_count ." | ". $last_name_9 ." / ". $survey_base9_count?></td>
    </tr>

    <tr>
      <td>Roxas</td>
      <td><?= $base10_count ." | ".$last_name_10 ." / ". $survey_base10_count?></td>
    </tr>

  </table>
