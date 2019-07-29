<?php
include 'DB.php';

$total = 0;
date_default_timezone_set('Asia/Kolkata');

$current = "";

function rounds($data) {
    $tempo = json_encode($data);
    $con = mysqli_connect($tempo["host"],$tempo["username"],$tempo["password"]) or die(mysqli_error());
    mysqli_select_db($con,$tempo["database"]);
    $current = "round".$tempo["round"];
    $perils = mysqli_query($con,"SELECT $current from perils WHERE $current=1");
    if ($perils === false) {
        var_dump(mysqli_error($con));
    }
    else {

    }
}


//for updating the rank... Used to get the rank list.
if (isset($_POST["update"])) {
	$val = $_POST['update'];
	$conn = mysqli_connect($host,$username,$password) or die(mysqli_error());
	mysqli_select_db($conn,$database) or die(mysqli_error($conn));
	$que = mysqli_query($conn,"SELECT team_id,worth FROM participants ORDER BY worth DESC");
	if($que === FALSE) {
		die(mysqli_conn($conn));
	}
	else{
		echo json_encode(mysqli_fetch_all($que,MYSQLI_NUM));
	}
	mysqli_close($conn);
}

//to update in insured assets on submission in member.php.
else if (isset($_POST["all_checked"])) {
	$current = "";
	$conn = mysqli_connect($host,$username,$password) or die(mysqli_error());
	mysqli_select_db($conn,$database) or die(mysqli_error($conn));

	$checkLogin = $_POST["all_checked"];
		for ($i = 0; $i <= 31; ++$i)
			$checkLogin = str_replace(chr($i), "", $checkLogin); 

		$checkLogin = str_replace(chr(127), "", $checkLogin);
		$received_data = json_decode(trim($checkLogin),true);

		$subs = mysqli_query($conn,"SELECT submissions FROM participants WHERE team_id = '".$received_data['team_id']."'");
		$sub = json_decode(mysqli_fetch_row($subs)[0],false);

		$isInLimit = intval(mysqli_fetch_row(mysqli_query($conn,"SELECT SUM(premium) FROM perils WHERE peril_id IN ('".implode("','", $received_data['data'])."')"))[0]);
		if ($received_data['update'] == "yes") {
			echo json_encode($isInLimit);
		}
		else{
		$thelimit = 0.05*intval(mysqli_fetch_row(mysqli_query($conn,"SELECT worth FROM participants WHERE team_id = '".$received_data['team_id']."'"))[0]);
		if ($isInLimit > $thelimit) {
			echo "The total premium exceed the 5% of total worth ...";	
		}
		else {

	if ($round1 * $round1_start < 0) {
		$current = '1';
	}
	else if ($round2 * $round2_start < 0) {
		$current = '2';
	}
	else if ($round3 * $round3_start <= 0) {
		$current = '3';
	}
	else if ($end < 0) {
		$current = '4';
	}
	// var_dump($sub);
	// add && $sub[$current] == 0  for final server.
	if ($current != 4) {
	if($current != "" && $sub[$current] == 0) {
		
		$sub[$current] = 1;
		$subs = mysqli_query($conn,"UPDATE participants SET submissions = '".json_encode($sub)."' WHERE team_id = '".$received_data['team_id']."'");

		$round_perils = mysqli_query($conn,"SELECT peril_id,asset_affected FROM perils WHERE round".$current." = 1");
		$the_assets = mysqli_query($conn,"SELECT assets from participants WHERE team_id='".$received_data["team_id"]."'");
		$after_asset = json_decode(mysqli_fetch_all($the_assets,MYSQLI_NUM)[0][0],false);

		foreach (mysqli_fetch_all($round_perils,MYSQLI_NUM) as $key) {
			if (!in_array(trim($key[0]), $received_data["data"])) {
				$ind = array_search(trim($key[1]), $after_asset);
				if ($ind !== FALSE){
					unset($after_asset[$ind]);
				}
			}
		}

		$worth_q = mysqli_query($conn,"SELECT SUM(value) FROM assets WHERE asset_id IN ('".implode("','",$after_asset)."')");

		// worth updated
		$worth_q = mysqli_query($conn,"UPDATE participants SET worth = ".mysqli_fetch_row($worth_q)[0]." WHERE team_id = '".$received_data['team_id']."'");

		$checkLogin = "[";
		foreach ($after_asset as $key) 
			$checkLogin .= "\"".$key."\",";

		$checkLogin[strlen($checkLogin) - 1] = "]";

		$query1 = mysqli_query($conn,"UPDATE participants SET insurance='".json_encode($received_data['data'])."' WHERE team_id='".$received_data["team_id"]."'");
		$query2 = mysqli_query($conn,"UPDATE participants SET assets = '".$checkLogin."' WHERE team_id = '".$received_data["team_id"]."'");
		if ($query1 === FALSE) {
			echo json_encode(mysqli_error($conn));
		}
		else{
			echo json_encode("true");
		}
	}
	else {
		echo "You cannot Submit ... ";
	}
		}
	else if ($current == 4){
		echo json_encode("true");
	}
	
}
}
}

else if(isset($_POST['selected'])){
	$no_use = $_POST['selected'];
	$conn = mysqli_connect($host,$username,$password) or die(mysqli_error());
	mysqli_select_db($conn,$database) or die(mysqli_error($conn));
}


//some random request given... this is my debugging message anyway.
else {
	echo json_encode(array(1,2,3,4,5,6,7,8,9));
}

?>