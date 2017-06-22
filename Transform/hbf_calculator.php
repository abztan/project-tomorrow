<?php
  include_once "_functions-general.php";
  include_once "_controller-transform.php";

	echo "<br/>BDAY ".$bday = "11/08/2016";
	echo "<br/>WDAY ".$wday = "2/6/2017";
	echo "<br/>SEX ".$sex = "1";
	echo "<br/>WEIGHT ".$weight_value = "7.1";
	echo "<br/>HEIGHT ".$height_value = "74.1";
	$b_day = date_create($bday);
	$w_day = date_create($wday);
	$today = new DateTime();

	$b_day = $b_day->format('Y-m-d');
	$w_day = $w_day->format('Y-m-d');
	$today = $today->format('Y-m-d');
	if($wday != "") {
		echo "<br/>MA ".$month_age = dateDifference($b_day,$w_day);
	}
	else {
		echo "<br/>MA ".$month_age = dateDifference($b_day,$today);
	}

	if($month_age > 60) {
		$wasting_score = compute_bmi_score($bday,$wday,$weight_value,$height_value,$sex);
		echo "<Br/>Measure: ".$measure = "BMI";

		echo "<br/>Value: ".$wasting_score;
		if($wasting_score <= -3 && $wasting_score >= -5)
			$condition = "SAM";
		else if($wasting_score <= -2 && $wasting_score > -3)
			$condition = "MAM";
		else if($wasting_score > -2)
			$condition = "Normal";
		else {
			$condition = "Undefined";
		}
		echo "<br/>Condition: ".$condition;
	}
	else {
		echo "<br/>Measure: ".$measure = "WHZ";
		echo "<br/>Value: ".$wasting_score = compute_wasting_score($weight_value,$height_value,$sex);
		if($wasting_score <= -3 && $wasting_score >= -5)
			$condition = "SAM";
		else if($wasting_score <= -2 && $wasting_score > -3)
			$condition = "MAM";
		else if($wasting_score > -2)
			$condition = "Normal";
		else {
			$condition = "Undefined";
		}

		echo "<br/>Condition: ".$condition;
	}

  function compute_wasting_score($weight,$height,$sex) {
	  //kilos
	  $weight = 12;
	  $height = 65;
	  $sex = 1;
	  $z_score = 0;

		if($height < 45 || $height > 120)
			$z_score = 99999;
		else {
		  if($height >= 65) {
		    $table = "wfhanthro";
		    $measure = "height";
		  }
		  else {
		    $table = "wflanthro";
		    $measure = "length";
		  }

		  $conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		  $dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		  $query = "SELECT * FROM $table WHERE sex ='$sex' AND $measure = '$height'";
		  $result = pg_query($dbconn, $query);
		  $ref = pg_fetch_array($result,NULL,PGSQL_BOTH);

		  //collect row
		  $l_val = $ref['l'];
		  $m_val = $ref['m'];
		  $s_val = $ref['s'];

		  //compute for z-score
		  $a = pow($weight/$m_val,$l_val) - 1;
		  $b = $s_val * $l_val;
		  $z_score = $a/$b;

		  if($z_score < -3) {
		    $exp = 1/$l_val;
		    $a = 1 + ($l_val*$s_val*-3);
		    $sd3_neg = $m_val * pow($a,$exp);
		    $sd23_neg = ($m_val * pow((1+($l_val*$s_val*-2)),$exp)) - $sd3_neg;
		    $z_score = -3 - (($sd3_neg-$weight)/$sd23_neg);
		  }

		  else if($z_score > 3) {
		    $exp = 1/$l_val;
		    $a = 1+ ($l_val*$s_val*3);
		    $sd3 = $m_val * pow($a,$exp);
		    $sd23 = $sd3 - $m_val * pow((1+$l_val*$s_val*2),$exp);
		    $z_score = 3 + (($weight - $sd3)/$sd23);
		  }

		  //SAM = -3 MAM = -2
		  $z_score = round($z_score,2);
		}

	  return $z_score;
	}

  function compute_bmi_score($birthday,$w_date,$weight,$height,$sex) {
    global $dbconn;
    $birthday = new DateTime($birthday);
    $w_date = new DateTime($w_date);
    $diff = $birthday->diff($w_date);
    $age = $diff->format('%a');
    $decimal_age = round($age/30.4375,1);

  	$age_floor = floor($decimal_age);
    $age_ceil = floor($decimal_age)+1;
    $age_diff = round($decimal_age-$age_floor,2);

		//kilos
	  //$weight = 3.1;
	  //$height = 50.4;
	  //$sex = 2;

		//BMI
		$h_val = $height/100;
		$h_val = pow($h_val,2);
		$value = $weight/$h_val;
		$bmi_value = round($value,2);
	  $z_score = 0;

    if($age_diff > 0) {
      $query = "SELECT * FROM bfawho WHERE sex ='$sex' AND age = '$age_floor'";
  	  $result = pg_query($dbconn, $query);
  	  $ref = pg_fetch_array($result,NULL,PGSQL_BOTH);
      $l_fl_val = $ref['l'];
  	  $m_fl_val = $ref['m'];
  	  $s_fl_val = $ref['s'];
      $query = "SELECT * FROM bfawho WHERE sex ='$sex' AND age = '$age_ceil'";
  	  $result = pg_query($dbconn, $query);
  	  $ref = pg_fetch_array($result,NULL,PGSQL_BOTH);
      $l_ce_val = $ref['l'];
  	  $m_ce_val = $ref['m'];
      $s_ce_val = $ref['s'];
      $l_val = $l_fl_val + ($age_diff*($l_ce_val-$l_fl_val));
      $m_val = $m_fl_val + ($age_diff*($m_ce_val-$m_fl_val));
      $s_val = $s_fl_val + ($age_diff*($s_ce_val-$s_fl_val));
    }

    else {
      $query = "SELECT * FROM bfawho WHERE sex ='$sex' AND age = '$age_ceil'";
  	  $result = pg_query($dbconn, $query);
  	  $ref = pg_fetch_array($result,NULL,PGSQL_BOTH);

  	  //collect row
  	  $l_val = $ref['l'];
  	  $m_val = $ref['m'];
  	  $s_val = $ref['s'];
    }

	  //compute for z-score
	  $a = pow($bmi_value/$m_val,$l_val) - 1;
	  $b = $s_val * $l_val;
	  $z_score = $a/$b;

	  if($z_score < -3) {
	    $exp = 1/$l_val;
	    $a = 1 + ($l_val*$s_val*-3);
	    $sd3_neg = $m_val * pow($a,$exp);
	    $sd23_neg = ($m_val * pow((1+($l_val*$s_val*-2)),$exp)) - $sd3_neg;
	    $z_score = -3 - (($sd3_neg-$bmi_value)/$sd23_neg);
	  }

	  else if($z_score > 3) {
	    echo "<br/>exp ".$exp = 1/$l_val;
	    echo "<br/>z ".$a = 1+ ($l_val*$s_val*3);
	    echo "<br/>z ".$sd3 = $m_val * pow($a,$exp);
	    echo "<br/>z ".$sd23 = $sd3 - $m_val * pow((1+$l_val*$s_val*2),$exp);
	    $z_score = 3 + (($bmi_value - $sd3)/$sd23);
	  }

	  //SAM = -3 MAM = -2
	  $z_score = round($z_score,2);

    return $z_score;
  }


  function dateDifference($date_1,$date_2)
  {
      $datetime1 = date_create($date_1);
      $datetime2 = date_create($date_2);

      $interval = date_diff($datetime1, $datetime2);

   		$year = $interval->format('%y');
   		$month = $interval->format('%m');
   		$day = $interval->format('%d');

  		$year = $year*12;
  		if($day > 15) {
  			$month++;
  		}

  		$month = $month+$year;

  		return $month;
  }


	?>
