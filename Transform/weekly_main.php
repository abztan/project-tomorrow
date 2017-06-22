<?php
  include_once "_functions-general.php";
  include_once "_controller-transform.php";
  include_once "../user_session.php";
  global $username;
  $application_pk = $_GET['application_pk'];
  $query = "SELECT community_id FROM list_transform_application WHERE id='$application_pk'";
  $result = pg_query($db_connect,$query);
  $community = pg_fetch_array($result,NULL,PGSQL_BOTH);
  $community_id = $community['community_id'];
?>

<h1></h1>
<table class="highlight">
  <thead>
    <tr>
      <th></th>
      <th>WK1</th>
      <th>WK2</th>
      <th>WK3</th>
      <th>WK4</th>
      <th>WK5</th>
      <th>WK6</th>
      <th>WK7</th>
      <th>WK8</th>
      <th>WK9</th>
      <th>WK10</th>
      <th>WK11</th>
      <th>WK12</th>
      <th>WK13</th>
      <th>WK14</th>
      <th>WK15</th>
      <th>WK16</th>
    </tr>
  </thead>
  <tbody>
    <?php
      for($a=1;$a<4;$a++) {
        if(1 === $a) {
          $title = "Visitor Count";
          $column = "count_visitor";
        }
        else if(2 === $a) {
          $title = "Children Count";
          $column = "count_child";
        }
        else if(3 === $a) {
          $title = "Overall Nutripack";
          $column = "nutripack_other";
        }

        echo "<tr>
          <td class='text'>$title</td>";

        for($i=1;$i<17;$i++) {
          $query = "SELECT *
                    FROM log_transform_weekly
                    WHERE fk_application_pk = '$application_pk'
                    AND week_number = '$i'";
          $result = pg_query($db_connect,$query);
          $weekly = pg_fetch_array($result,NULL,PGSQL_BOTH);

     			$identity = $a."_".$i;
     			$x = "input_".$identity;
     			$y = "div_wk".$identity;
     			$default = $weekly[$column];

     			echo "
     						<td>
     							<input type='number' class='weekly' min='0' id='$x' title='($community_id) $title WK$i' onchange='display_weekly_update(\"$identity\")' placeholder='-' value='$default'>
     							<div id='$y' hidden>
     								<span class='material-icons' style='cursor:pointer; color:#e05b4a;' onclick='update_weekly_data($application_pk,\"$column\",\"$identity\",1,$i,\"$username\")'>check</span>
     								<span hidden onclick='update_weekly_data($application_pk,\"$column\",\"$identity\",1,$i,\"$username\")'>x</span>
     							</div>
     						</td>";
     		}

        echo "</tr>";
      }
    ?>
    <tr>
      <?php
        $title = "Double Lesson";
        $column = "double_lesson";
        echo "<td class='text'>$title</td>";

        for($i=1;$i<17;$i++) {
          $a = 4;
          $query = "SELECT *
                    FROM log_transform_weekly
                    WHERE fk_application_pk = '$application_pk'
                    AND week_number = '$i'";
          $result = pg_query($db_connect,$query);
          $weekly = pg_fetch_array($result,NULL,PGSQL_BOTH);

          $identity = $a."_".$i;
          $x = "input_".$identity;
          $y = "div_wk".$identity;
          $default = $weekly[$column];

          echo "
                <td>
                  <input title='($community_id) $title WK$i' type='checkbox' id='$x' ".tick_checkbox($default,"1")." onchange='display_weekly_update(\"$identity\")'/>
                  <div id='$y' hidden>
                    <span class='material-icons' style='cursor:pointer; color:#e05b4a;' onclick='update_weekly_data($application_pk,\"$column\",\"$identity\",1,$i,\"$username\")'>check</span>
                    <span hidden onclick='update_weekly_data($application_pk,\"$column\",\"$identity\",1,$i,\"$username\")'>x</span>
                  </div>
                </td>";
        }
      ?>
    </tr>
    <tr>
      <?php
        $title = "Class Cancelled";
        $column = "is_cancelled";
        echo "<td class='text'>$title</td>";

        for($i=1;$i<17;$i++) {
          $a = 5;
          $query = "SELECT *
                    FROM log_transform_weekly
                    WHERE fk_application_pk = '$application_pk'
                    AND week_number = '$i'";
          $result = pg_query($db_connect,$query);
          $weekly = pg_fetch_array($result,NULL,PGSQL_BOTH);

          $identity = $a."_".$i;
          $x = "input_".$identity;
          $y = "div_wk".$identity;
          $default = $weekly[$column];

          echo "
                <td>
                  <input title='($community_id) $title WK$i' type='checkbox' id='$x' ".tick_checkbox($default,"1")." onchange='display_weekly_update(\"$identity\")'/>
                  <div id='$y' hidden>
                    <span class='material-icons' style='cursor:pointer; color:#e05b4a;' onclick='update_weekly_data($application_pk,\"$column\",\"$identity\",1,$i,\"$username\")'>check</span>
                    <span hidden onclick='update_weekly_data($application_pk,\"$column\",\"$identity\",1,$i,\"$username\")'>x</span>
                  </div>
                </td>";
        }
      ?>
    </tr>
  </tbody>
</table>
</html>
