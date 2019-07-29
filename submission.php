<?php
session_start();

if (!isset($_SESSION['ok'])) {
	header("location: main.php");
}
else {

	// echo "You have successfully submitted...";
	// echo $_SESSION["ok"];
	?>
	<html style="background-image: url('image1.jpg');">
	<head>
	<link rel="icon" href="tf.ico">
		
		<title>Logout</title>
	</head>
	<body>
	<link rel="stylesheet" href="http://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<div class="alert alert-success" role="alert">
	  <h4 class="alert-heading">Well done!</h4>
	  <p>You have successfully submitted your response for this round.</p>
	  <hr>
	  <p class="mb-0">You may logout and wait till the results are announced.</p>
	  <a href="main.php">Logout</a>
	</div>
	</body>
	<?php
	session_destroy();
}

?>