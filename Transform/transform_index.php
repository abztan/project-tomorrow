<?php
/*
error_reporting(-1);
$account_name = "Abraham Tan";
$account_type = "Superadmin";
$access_level = 1;
$account_base = 99;
$username = "abz";
*/

session_start();
if(empty($_SESSION['username']))
  header('location: /ICM/Login.php?a=2');
else {
  $username = $_SESSION['username'];
  $access_level = $_SESSION['accesslevel'];
  $account_base = $_SESSION['baseid'];
}
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
  <link type="text/css" rel="stylesheet" href="../css/tokyo.css" media="screen,projection"/>
  <link rel="shortcut icon" href="../media/ptfavicon.ico">
  <title>PT</title>
</head>

<nav>
  <div class="nav-wrapper">
    <a href="#" src="media/ptfavicon.ico" class="brand-logo right">P+</a>
    <ul>
      <li><a href="#" style="color:#e05b4a;">TRANSFORM</a></li>
      <li><a href="transform_index.php" style="color:#e05b4a;">Home</a></li>
      <li><a href="reports_index.php" style="color:#e05b4a;">Reports</a></li>
      <li hidden><a href="transform_index2.php">THRIVE</a></li>
    </ul>
  </div>
</nav>

<body>
  <br/>
  <div class="filter">
      <label>Year</label>
      <input placeholder="YYYY" id="set_year" type="number" min="2015" max="2100" value="" onchange="display_communities(); hide_all();">
      <label>Batch</label>
      <input placeholder="1, 2, 3" id="set_batch" autofocus type="number" class="batch_input" min="1" max="3" value="" onchange="display_communities(); hide_all();">
      <label>Base</label>
      <select id="set_base" onchange="display_communities(); hide_all();">
        <option value="" selected disabled>Select One</option>
        <option value="1">1. Bacolod</option>
        <option value="2">2. Bohol</option>
        <option value="3">3. Dumaguete</option>
        <option value="4">4. General Santos</option>
        <option value="5">5. Koronadal</option>
        <option value="6">6. Palawan</option>
        <option value="7">7. Dipolog</option>
        <option value="8">8. Iloilo</option>
        <option value="9">9. Cebu</option>
        <option value="10">10. Roxas</option>
      </select>
      <label>Community</label>
      <select id="set_community" onchange="display_community_main(); display_people_main('default'); display_attendance_main(); display_weekly_main(); show_all();">
        <option value="" disabled selected>Select One</option>
        <option value="" disabled>¯\_(ツ)_/¯</option>
      </select>
  </div>

  <div class="main">
    <ul class="collapsible" data-collapsible="accordion">
      <li id="information">
        <div id="inf_block" class="collapsible-header"><i class="material-icons">subject</i>Information</div>
        <div id="inf_header" class="collapsible-body" style="overflow: auto;">
          <div id="inf_contents"><i>Provide details on the filter above to populate contents.</i></div>
        </div>
      </li>
      <li id="people">
        <div id="peo_block" class="collapsible-header"><i class="material-icons">insert_emoticon</i>People</div>
        <div id="peo_header" class="collapsible-body" style="overflow: auto;">
          <div id="people_default">
            <i>Provide details on the filter above to populate contents.</i>
          </div>
          <div id="people_table">
            <div class="action_bar_2">
              <i class="material-icons live" title="Add Participant" style="color:" onclick="display_people_add();">person_add</i>
            </div><br/><br/>
    				<table class="highlight">
    					<thead>
    						<tr>
    							<th width="30%" onclick="display_people_main('name');" class="text"><span class= "live">Name</span></th>
    							<th width="20%" onclick="display_people_main('id');" class="text"><span class= "live">ID</span></th>
    							<th width="20%" onclick="display_people_main('class');"><span class= "live">Class</span></th>
    							<th width="20%">Status</th>
    							<th width="10%">Details</th>
    						</tr>
    					</thead>
          		  <tbody id="peo_contents">
              </tbody>
    				</table>
          <div>
        </div>
      </li>
      <li id="attendance">
        <div id="att_block" class="collapsible-header"><i class="material-icons">timeline</i>Attendance</div>
        <div id="att_header" class="collapsible-body" style="overflow: auto;">
          <div id="att_contents"><i>Provide details on the filter above to populate contents.</i></div>
        </div>
      </li>
      <li id="weekly">
        <div id="wee_block" class="collapsible-header"><i class="material-icons">equalizer</i>Weekly</div>
        <div id="wee_header" class="collapsible-body" style="overflow: auto;">
          <div id="wee_contents"><i>Provide details on the filter above to populate contents.</i></div>
        </div>
      </li>
      <li id="hbf" hidden>
        <div id="hbf_block" class="collapsible-header"><i class="material-icons">child_care</i>HBF</div>
        <div id="hbf_header" class="collapsible-body" style="overflow: auto;">
          <div id="hbf_contents"><i>Provide details on the filter above to populate contents.</i></div>
        </div>
      </li>
    </ul>
  </div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/transform_script.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/materialize.js"></script>
<script type="text/javascript">
  document.getElementById("people_table").style.display = "none";

  $(document).ready(function() {
    //set account defaults
    var account_base = "<?= $account_base;?>";

    //lock base selection
    if(account_base <= 90) {
      document.getElementById("set_base").value = account_base;
      document.getElementById("set_base").disabled = true;
    }

    //set filter defaults
    var current_year = new Date().getFullYear() - 1;
    document.getElementById("set_year").value = current_year;
  });

  function show_all() {
    document.getElementById("people_default").style.display = "none";
    document.getElementById("people_table").style.display = "";
  }

  function hide_all() {
    //resets accordion and displays default value
    document.getElementById("inf_header").style.display = "none";
    document.getElementById("peo_header").style.display = "none";
    document.getElementById("att_header").style.display = "none";
    document.getElementById("people_table").style.display = "none";
    document.getElementById("people_default").style.display = "";
    document.getElementById("wee_header").style.display = "none";

    document.getElementById("inf_contents").innerHTML = "<i>Provide details on the filter above to populate contents.</i>";
    document.getElementById("peo_contents").innerHTML = "<i>Provide details on the filter above to populate contents.</i>";
    document.getElementById("att_contents").innerHTML = "<i>Provide details on the filter above to populate contents.</i>";
    document.getElementById("wee_contents").innerHTML = "<i>Provide details on the filter above to populate contents.</i>";
  }
</script>

</body>
</html>
