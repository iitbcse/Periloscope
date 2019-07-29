<?php 

include "DB.php";
$con = mysqli_connect($host,$username,$password) or die(mysqli_error());
mysqli_select_db($con,$database) or die(mysqli_error($con));

function update_worth() {
    $default = mysqli_query($GLOBALS['con'],"UPDATE participants SET worth = 30000000");
    if ($default === false) {
        var_dump(mysqli_error($GLOBALS['con']));
    }
    else
        echo "WORTH SET TO DEFAULT | ";
}


function round0() {
    $q = mysqli_query($GLOBALS['con'],"SELECT asset_id FROM assets ORDER BY asset_id");
    $assets = mysqli_fetch_all($q,MYSQLI_NUM);
    var_dump($assets);

    $assets_array = "[";
    foreach ($assets as $i) {
        $assets_array .= "\"".$i[0]."\",";
    }
    $assets_array[strlen($assets_array) - 1] = "]";
    var_dump($assets_array);
    $q_ = mysqli_query($GLOBALS['con'],"UPDATE participants SET assets = '$assets_array'");
    $subs = "[0,0,0,0]";
    $qr = mysqli_query($GLOBALS['con'],"UPDATE participants SET submissions = '$subs'");
    if($q_ === false && $qr === false){
        die(mysqli_error($GLOBALS['con']));
    }
    else
        echo "ASSETS SET TO DEFAULT";
}


$vard = false;
if (isset($_POST["sub"])) {

    $user = mysqli_real_escape_string($con,$_POST['user']);
    $pass = mysqli_real_escape_string($con,$_POST['pass']);
            if (($user === "CleverBrain") && ($pass === "Batayatohtha")) {
                /* Redirect browser */
                update_worth();
                round0();
                $vard = true;
            }
        else {
            echo "<div class=\"alert alert-danger\" role=\"alert\">Invalid Username or Password!</div>";
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
            window.location = "main.php";
    </script>
<?php }?>
</body>
</html>