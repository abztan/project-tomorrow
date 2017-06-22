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
  <div class="main">
    <br/>
    <div class="filter">
      <label>Year</label>
      <input placeholder="YYYY" id="set_year" type="number" min="2015" max="2100" value=""  onchange="generate_results();">
      <label>Batch</label>
      <input placeholder="1, 2, 3" id="set_batch" autofocus type="number" class="batch_input" min="1" max="3" value="" onchange="generate_results();">
    </div>

    <img src='../media/301.gif' hidden id='loader'>
    <div id="results">
    </div>
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

  function generate_results() {
    var year = document.getElementById("set_year").value;
    var batch = document.getElementById("set_batch").value;

    if(year > 0 && batch > 0) {
      var xmlhttp = null;
      if(typeof XMLHttpRequest != 'udefined'){
          xmlhttp = new XMLHttpRequest();
          if(year != 0 && batch !=0)
            $('#loader').show();
            $('#results').hide();
      }else if(typeof ActiveXObject != 'undefined'){
          xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
      }else
          throw new Error('You browser doesn\'t support ajax');

      xmlhttp.open('GET', '../transform/_controller-transform.php?command=gender_reports&year='+year+'&batch='+batch, true);
      xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
          window.results_container(xmlhttp);
      };
      xmlhttp.send(null);
    }
  }

  function results_container(xhr) {
      if(xhr.status == 200){
        $('#loader').hide();
        $('#results').show();
        document.getElementById('results').innerHTML = xhr.responseText;
      }else
          throw new Error('Server has encountered an error\n'+
              'Error code = '+xhr.status);
  }
</script>

</body>
</html>
