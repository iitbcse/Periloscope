<?php
session_start();

include 'DB.php';
date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION["sess_user"])) {
	header("location: main.php");
} else {

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
	<link rel="icon" href="tf.ico">
	<script type="text/javascript">
		function moneyFormatIndia(x) {
    x=x.toString();
    var lastThree = x.substring(x.length-3);
    var otherNumbers = x.substring(0,x.length-3);
    if(otherNumbers != '')
        lastThree = ',' + lastThree;
    var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
    return res;
}
	</script>
	<link rel="stylesheet" href="css/bootstrap.min.css">

	<link rel="stylesheet" href="http://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">


	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<title>Just One Submission!!</title>
	<style type="text/css" >
	@import url('https://fonts.googleapis.com/css?family=Numans');

html,body {
		padding-top: 8vh;
		background-image: url('blurred.jpg');
		background-repeat: no-repeat;
		background-attachment: fixed;
		color: #21085d;
		font-family: Numans,  sans-serif;
		font-size: 100%;
	}
	tbody {
		overflow-y: auto; 
	}
</style>
</head>
<body>
	<!-- Navigation Bar(s)  -->
	<nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #f6f6f6;">

    <div class="navbar-brand">Periloscope Challenge</div>

    <div class="collapse navbar-collapse" id="navbarColor03">
      <ul class="navbar-nav mr-auto" style="color: #097033 !important;">
      	<li class="nav-item">
          <div class="nav-link" ><span style="color: black;">Welcome, <?=$_SESSION['sess_user'];?>!</span></div>
        </li>
        <li class="nav-item">
          <div class="nav-link"><b>Current Worth:</b> $<span id="one"><script>$('#one').text(moneyFormatIndia(<?=$amount[0];?>));</script></span></div>
        </li>
        <li class="nav-item">
          <div class="nav-link"><b>Max Premium amount available:</b> $<span id="two"><script>$('#two').text(moneyFormatIndia(<?=$insurable;?>));</script></span></div>
        </li>
        <li class="nav-item">
        	<!-- Update the span on click on every checkbox -->
          <div class="nav-link"><b>Total Premium Cost:</b> $<span id="lets_update">0</span></div>
        </li>
      </ul>
    </div>

    <button onclick="document.location.href='logout.php'" class="btn btn-default">Logout</button>
  </nav>
  <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #f6f6f6; margin-top: 7vh;">
	  	<div class="navbar-brand">Deadlines: </div>
	  	<div class="collapse navbar-collapse" id="navbarColor03">
      <ul class="navbar-nav mr-auto" style="color: #097033 !important;">
      	<li class="nav-item">
          <div class="nav-link"><b>Round 1:</b> 13th Dec. 10:00 PM </div>
        </li>
        <li class="nav-item">
          <div class="nav-link"><b>Round 2:</b> 14th Dec. 10:30 AM</div>
        </li>
        <li class="nav-item">
          <div class="nav-link"><b>Round 3:</b> 14th Dec. 12:00 PM</div>
        </li>
        <li class="nav-item">
          <b><div class="nav-link" id="current"></div></b>
        </li>
      </ul>
    </div>
   <a id="perils" href="#"><button class="btn btn-default">Perils</button></a>
  </nav>

	<div style="margin-left: 0.5vw; margin-right: 1vw;">
		<div style="position: relative;">
	<ol id="update-ranking" style="width: auto;
		margin-left: 5px;
		float: right;
		">Ranking: </ol>		
</div>
	<div id="aTable" style="width: 65%; float: left;">
		<table id="selector" class="table" border="2px">
			<thead class="thead-dark">
				<tr>
					<th>Policy Code</th>
					<th>Peril Name</th>
					<th>Asset Affected <br>by the Peril  </th>
					<th>Asset</th>
					<th>Premium<br>(In $)</th>
					<th>Is Insured?</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$avail = mysqli_fetch_row(mysqli_query($con,"SELECT assets FROM participants WHERE team_id ='".$_SESSION['sess_user']."'"));
				$rows = mysqli_query($con,"SELECT * FROM perils WHERE asset_affected IN ('".implode("','",json_decode($avail[0],false))."')");
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
					<td class=\"$count\"><script>$('.$count').text(moneyFormatIndia(".$row[4]."));</script></td>
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
			<input id="submit-all" class="btn btn-primary btn-lg btn-block" style="border-radius: 4px; font-family: verdana; font-size: 100%;" type="button" value="SUBMIT" name="final_sub">
		</form>
	</div>
	<script type="text/javascript">var the_user = "<?=$_SESSION["sess_user"]?>";</script>

	<script type="text/javascript" src="member.js"></script>
	<script type="text/javascript">
		function downloader() {
			if (<?=$round3_start?> < 0) {
				console.log("in round 3");
				$('#perils').attr("href","round_2.xlsx");
				$('#perils').attr("download","Roun2.xlsx");
				$('#current').text("Current Round #3");
				// update_ranking();
			}
			else if (<?=$round2_start?> < 0) {
				console.log("in round 2;;;" + <?=$round3_start;?>);

				$('#perils').attr("href","round_1.xlsx");
				$('#perils').attr("download","Round1.xlsx");
				$('#current').text("Current Round #2");
				update_ranking();
			}
			else if (<?=$round1_start?> < 0) {
				$('#perils').attr("href","#");
				$('#current').text("Current Round #1");
				update_ranking();
			}
			else if (<?=$round3;?> < 0) {
				$('#perils').attr("href","round_3.xlsx");
				$('#perils').attr("download","Round3.xlsx");

			}
		}
		downloader();
	</script>
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
