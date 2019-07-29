<?php
include 'DB.php';

session_start();
if (!isset($_SESSION["sess_user"])) {
	header("location: main.php");
} else {
	function moneyFormatIndia($num) {
		$explrestunits = "" ;
		if(strlen($num)>3) {
			$lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
        	if($i==0) {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            } else {
            	$explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
    	$thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}

$con = mysqli_connect($host,$username,$password,$database) or die(msqli_error());
$amount = mysqli_query($con,"SELECT worth from participants where team_id = \"" . $_SESSION["sess_user"]."\"");
if ($amount === FALSE) {
	die(mysqli_error($con));
}
else {
	$amount = mysqli_fetch_row($amount);
}
$insurable = strval(0.05 * intval($amount[0]));
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="http://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<link rel="stylesheet" href="http://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<!-- Countdown library -->
	<!-- <script src="/bower_components/jquery.countdown/dist/jquery.countdown.js"></script> -->

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<title>Just One Submission!!</title>
	<style type="text/css" >
	@import url('https://fonts.googleapis.com/css?family=Numans');

html,body {
		padding-top: 7vh;
		background: url('image1.jpg');
		color: #2a73b9;
		font-family: Numans,  sans-serif;
		font-size: 100%;
	}
	tbody {
		overflow-y: auto; 
	}
	#topnav {
		color: white;
		top: 0; left: 0;
		box-sizing: border-box;
		padding: 0 50px 0 0;
		width: 100%;
		background-color: #333;
		line-height: 48px;
	}
	/*#fixed-header {
		position: fixed;
		top: 0;
		display: none;
		background-color: white;
	}*/
</style>
</head>
<body>
	<!-- Navigation Bar  -->
	<nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">

    <a class="navbar-brand" href="#">Periloscope Challenge</a>

    <div class="collapse navbar-collapse" id="navbarColor03">
      <ul class="navbar-nav mr-auto">
      	<li class="nav-item">
          <a class="nav-link" href="#"><span style="color: black;">Welcome, <?=$_SESSION['sess_user'];?>!</span></a>
        </li>
        <!-- Update the premium amout as required. -->
        <li class="nav-item">
          <a class="nav-link" href="#">Current Worth: $<span><?=moneyFormatIndia($amount[0]);?></span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Max Premium amount available: <span><?=moneyFormatIndia($insurable);?></span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Total Premium: $<span><?=moneyFormatIndia('0');?></span></a>
        </li>
      </ul>
    </div>

    <button onclick="document.location.href='logout.php'" class="btn btn-default">Logout</button>
  </nav>
	<!-- update the premium amount as when the user selects a check box. -->

	<div style="margin-top: 2vh; margin-left: 0.5vw; margin-right: 1vw;">
	<ol id="update-ranking" style="width: auto;
		margin-left: 5px;
		float: right;">Ranking: </ol>		

	<div id="aTable" style="width: 65%; float: left;">
		<table id="selector" class="table" border="2px">
			<thead class="thead-dark">
				<tr>
					<th>Policy Code</th>
					<th>Peril Name</th>
					<th> Policy Description <br> (Perils Covered) </th>
					<th>Asset</th>
					<th>Premium<br>(In $)</th>
					<th>Is Insured?</th>
				</tr>
			</thead>
			<tbody>
				<?php

				$rows = mysqli_query($con,"SELECT * FROM perils ORDER BY id");
				if ($rows === FALSE) {
					die(mysqli_error($con));
				}
				$count = 0;
				$asset_q = "SELECT asset_name FROM assets WHERE asset_id = \"";
				while($count < mysqli_num_rows($rows)){
					$row = mysqli_fetch_row($rows);
					$describe = mysqli_fetch_row(mysqli_query($con,$asset_q.$row[3]."\""))[0];
					echo "<tr>
					<td>".$row[1]."</td>
					<td>".$row[2]."</td>
					<td>".$describe."</td>
					<td>".$row[3]."</td>
					<td id=\"premium\">".moneyFormatIndia($row[4])."</td>
					<td class=\"clickable\">
					<input type=\"checkbox\" class=\"form-check-input\" id=\"chk$count\" name=\"yes\" style=\"margin-left: 2px;\" value={\"premium\":".$row[4].",\"peril_id\":\"".$row[1]."\"}> </td></tr>";
					$count = $count + 1;
				}
				?>
			</tbody>
		</table>
	</div>
	</div>

	<div style="clear: both;"></div>

	<div style="margin: 10px 10px; float: right;" >
		<form value="" method="POST">
			<!-- <button type="button"> Block level button </button> -->
			<input id="submit-all" class="btn btn-primary btn-lg btn-block" style="border-radius: 4px; font-family: verdana; font-size: 100%;" type="button" value="SUBMIT" name="final_sub">

			<?php 
			date_default_timezone_set("Asia/Kolkata"); 
			// var_dump(date("H:i:s,m-d-y",mktime(date("17:23:25,5-12-18") + 1*6*1000)));
			// var_dump(expression)
			// var_dump(date("H:i:s,m-d-y") > date("H:i:s,m-d-y",mktime(17,date('i')+1,0,date('m'),date('d'),date('y')))); 
			?>
		</form>
	</div>
	<!-- <script type="text/javascript" src="https://techfest.org/js/app.js"></script> -->
	<script type="text/javascript">var the_user = "<?=$_SESSION["sess_user"]?>";</script>


	<script type="text/javascript" src="member.js"></script>
</body>
</html>
<?php
		if (isset($_POST["ok"])) {
		echo "<script type=\"text/javascript\">console.log(".$_POST['ok'].")</script>";

			$_SESSION["ok"] = TRUE;
		}
	?>
<?php
exit;
}
?>
