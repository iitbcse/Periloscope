<?php
session_start();
include "DB.php";
ini_set('display_errors', '1');
error_reporting(E_ALL);
?>

<?php 
$vard = false;
if (isset($_POST["sub"])) {

	$con = mysqli_connect($host,$username,$password) or die(mysqli_error());
	mysqli_select_db($con,$database) or die("cannot select DB");
	$user = mysqli_real_escape_string($con,$_POST['user']);
	$pass = mysqli_real_escape_string($con,$_POST['pass']);
	$query1 = mysqli_query($con,"SELECT * FROM participants WHERE team_id='".$user."' AND password='".$pass."'");

	if ($query1 === FALSE) {
		die(mysqli_error($con));
	}
	else{
		$numrows = mysqli_num_rows($query1);
		if ($numrows!=0) {
			while ($row = mysqli_fetch_assoc($query1)) {
				$dbusername = $row['team_id'];
				$dbpassword = $row['password'];
			}
			if (($user === $dbusername) && ($pass === $dbpassword)) {
				$subs = mysqli_query($con,"SELECT submissions FROM participants WHERE team_id = '".$user."'");
				if ($subs === FALSE) {
					die(mysqli_error($con));
				}
				$value = json_decode(mysqli_fetch_row($subs)[0],false);

				if ($round3_start < 0) {
						if ($value[3] == 0) {
							$_SESSION['sess_user'] = $user;
							/* Redirect browser */
							$vard = true;
						}
					else{
							echo "<div class=\"alert alert-danger\" role=\"alert\">Already Submitted!!</div>";
						}}
						else if ($round2_start < 0) {
							if ($value[2] == 0) {
							$_SESSION['sess_user'] = $user;
							/* Redirect browser */
							$vard = true;
						}
					else{
							echo "<div class=\"alert alert-danger\" role=\"alert\">Already Submitted!!</div>";
						}}
						else if ($round1_start < 0) {
							if ($value[1] == 0) {
							$_SESSION['sess_user'] = $user;
							/* Redirect browser */
							$vard = true;
						}
						else{
							echo "<div class=\"alert alert-danger\" role=\"alert\">Already Submitted!!</div>";
						}
						}
						else if ($end < 0) {
							$_SESSION['sess_user'] = $user;
							/* Redirect browser */
							$vard = true;
						}
				}
		else 
			echo "<div class=\"alert alert-danger\" role=\"alert\">Invalid  bhal blah blahUsername or Password!</div>";
		}
		else {
			echo "<div class=\"alert alert-danger\" role=\"alert\">Invalid toe two two Username or Password!</div>";}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="icon" href="tf.ico">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="main.css">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Login Page</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<?php
	if (!$vard) {
		?>
	<div class="container">
		<div class="d-flex justify-content-center h-100">
			<div class="card">
				<div class="card-header">
					<h3>Sign In</h3>
				</div>
				<div class="card-body">
					<form action="" method="POST">
						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="user" class="form-control" placeholder="username" required="true">
						</div>
						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="pass" class="form-control" placeholder="password" required="true">
						</div>
						<div class="form-group">
							<button type="submit" class="btn float-right login_btn" name="sub">Login</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
	else{?>
	<script type="text/javascript">
		if(<?=$vard;?>)
			window.location = "member.php";
	</script>
<?php }?>
</body>
</html>