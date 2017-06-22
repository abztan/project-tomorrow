<?php
  include_once "_functions-general.php";
  include_once "_controller-transform.php";
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

<body>
  <label>Year</label>
  <input placeholder="YYYY" id="set_year" type="number" min="2015" max="2100" value=""  onchange="generate_results();">
  <label>Batch</label>
  <input placeholder="1, 2, 3" id="set_batch" autofocus type="number" class="batch_input" min="1" max="3" value="" onchange="generate_results();">

  <div id="results"></div>

  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script type="text/javascript" src="../js/transform_script.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="../js/materialize.js"></script>
  <script type="text/javascript">
    function generate_results() {
      var year = document.getElementById("set_year").value;
      var batch = document.getElementById("set_batch").value;

      var xmlhttp = null;
      if(typeof XMLHttpRequest != 'udefined'){
          xmlhttp = new XMLHttpRequest();
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

    function results_container(xhr) {
        if(xhr.status == 200){
          document.getElementById('results').innerHTML = xhr.responseText;
        }else
            throw new Error('Server has encountered an error\n'+
                'Error code = '+xhr.status);
    }
  </script>
</body>
</html>
