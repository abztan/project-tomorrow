<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
  <link type="text/css" rel="stylesheet" href="../css/tokyo.css" media="screen,projection"/>
  <link rel="shortcut icon" href="../media/ptfavicon.ico">
  <title>PT</title>
  <style media="screen">
    .toggle_area {
      display: none;
      background: #badbc8;
    }

    .expand {
    }
  </style>
</head>
<table class="highlight" style='margin: 50px 50px 50px 50px;'>
  <tr>
    <th>BASE</th>
    <th>BIB KIT</th>
    <th>DISPERSAL</th>
    <th>BALANCE</th>
    <th>CAPITAL</th>
    <th>REPAYMENT RATE</th>
  </tr>
    <?php
      include_once "../db/db_connect.php";
      include_once "_functions-general.php";
      $dispersal_total = 0;
      $balance_total = 0;
      $capital_total = 0;
      $base_x = 1;
      $query = "SELECT base_id, bib_class, sum(balance) as balance, sum(capital) as capital, sum(kit_count) as dispersal
                FROM list_bib_participant
                LEFT JOIN list_transform_application
                ON list_bib_participant.fk_application_pk = list_transform_application.id
                WHERE community_id ilike '16___2%'
                GROUP BY base_id, bib_class
                ORDER BY base_id, bib_class";
      $result = pg_query($db_connect, $query);
      while($bib = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
        $base_id = $bib['base_id'];
        if ($base_x != $base_id) {
          echo "<tr>";
          echo "<td></td>
                <td></td>
                <td><strong>$dispersal_total</strong></td>
                <td class='numeric'><strong>".number_format($balance_total,2, '.', ',')."</strong></td>
                <td class='numeric'><strong>".number_format($capital_total,2, '.', ',')."</strong></td>
                <td><strong>".round((($capital_total-$balance_total)/$capital_total)*100,1)."</strong></td></tr>";

          $base_x = $base_id;
          $dispersal_total = 0;
          $balance_total = 0;
          $capital_total = 0;

          $bib_class = $bib['bib_class'];
          $dispersal = $bib['dispersal'];
          $balance = $bib['balance'];
          $capital = $bib['capital'];
          $repayment_rate = round((($capital-$balance)/$capital)*100,1);

          echo "<tr>";
          echo "<td class='text'>".getBaseName($base_id)."</td>";
          echo "<td class='text'>".getBIB_kit_name($bib_class)."</td>";
          echo "<td>$dispersal</td>";
          echo "<td class='numeric'>".number_format($balance,2, '.', ',')."</td>";
          echo "<td class='numeric'>".number_format($capital,2, '.', ',')."</td>";
          echo "<td>$repayment_rate</td>";
          echo " </tr>";
          $balance_total = $balance_total + $balance;
          $capital_total = $capital_total + $capital;
          $dispersal_total = $dispersal_total + $dispersal;


        }
        else {
          $bib_class = $bib['bib_class'];
          $dispersal = $bib['dispersal'];
          $balance = $bib['balance'];
          $capital = $bib['capital'];
          $repayment_rate = round((($capital-$balance)/$capital)*100,1);

          echo "<tr class='expand'>";
          echo "<td class='text'>".getBaseName($base_id)."</td>";
          echo "<td class='text'>".getBIB_kit_name($bib_class)."</td>";
          echo "<td>$dispersal</td>";
          echo "<td class='numeric'>".number_format($balance,2, '.', ',')."</td>";
          echo "<td class='numeric'>".number_format($capital,2, '.', ',')."</td>";
          echo "<td>$repayment_rate</td>";
          echo " </tr>";

          $subq = "SELECT community_id, sum(balance) as balance, sum(capital) as capital, sum(kit_count) as dispersal
                   from list_bib_participant
                   left join list_transform_application
                   ON list_bib_participant.fk_application_pk = list_transform_application.id
                   WHERE base_id = '$base_id' and bib_class = '$bib_class' and community_id ilike '16___2%'
                   GROUP BY community_id";
          $subr = pg_query($db_connect,$subq);
          while($sub = pg_fetch_array($subr,NULL,PGSQL_BOTH)) {
          echo "<tr class='toggle_area'>
                  <td></td>
                  <td class='text'>".$sub['community_id']."</td>
                  <td>".$sub['dispersal']."</td>
                  <td class='numeric'>".$sub['balance']."</td>
                  <td class='numeric'>".$sub['capital']."</td>
                  <td>".round((($sub['capital']-$sub['balance'])/$sub['capital'])*100,1)."</td>
                </tr>";
          }

          $balance_total = $balance_total + $balance;
          $capital_total = $capital_total + $capital;
          $dispersal_total = $dispersal_total + $dispersal;
        }
      }

      echo "<tr>";
      echo "<td></td>
            <td></td>
            <td><strong>$dispersal_total</strong></td>
            <td class='numeric'><strong>".number_format($balance_total,2, '.', ',')."</strong></td>
            <td class='numeric'><strong>".number_format($capital_total,2, '.', ',')."</strong></td>
            <td><strong>".round((($capital_total-$balance_total)/$capital_total)*100,1)."</strong></td></tr>";

      ?>

</table>

<script type="text/javascript">
  $(document).ready(function(){
    $('.expand').click(function(){
        $(this).nextUntil('.expand').slideToggle();
         return false;
    });
  });
</script>
