<?php
session_start();
include('connection.php');
$method=$_SERVER['REQUEST_METHOD'];
if($method=='POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "select count(*) from user where username='$username' and password='$password';";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($res)[0];
    if($row>0) {
        $_SESSION["username"] = $username;
		header("Location: /faculty_allocation/index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</head>

<body>
    <div class="container">
        <div class="row mt-4 mb-2 justify-content-center">
            <div class="col-auto">
                <h2 class="text-primary">MITM | FACULTY ALLOCATION</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-8 col-lg-6 col-xl-4 col-sm-8 col-xs-8 text-center p-2 pt-3 border border-primary rounded bg-light">
                <h2 class="text-primary">Log-In</h2>
            </div>
            <div class="w-100"></div>
            <div class="col-8 col-lg-6 col-xl-4 col-sm-8 col-xs-8 p-4 border border-primary border-top-0 rounded bg-light">
                <form action="" method="POST">
                    <div class="form-group">
                            <label class="text-primary" for="username">User Name:</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter User Name" name="username" required>
                    </div>
                    <div class="form-group">
                        <label class="text-primary" for="pwd">Password:</label>
                        <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password" required>
                    </div>
                    <div class="text-danger"><?php if($method=='POST') echo('Invalid User'); ?></div> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>