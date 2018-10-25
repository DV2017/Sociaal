<?php
require 'config/config.php';

if(isset($_SESSION['username'])) {
    //userLoggedIn is a session varaiable corresponding to the logged in user
    $userLoggedIn = $_SESSION['username'];
    //$firstname = $_SESSION['first_name'];
    $query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn' ");
    $user = mysqli_fetch_array($query);
} else {
    header("Location: register.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to my social network site</title>
    <!-- javascript links -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <!-- css links -->
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="top_bar">

        <div class="logo">
            <a href="index.php">Community</a>
        </div>

        <nav>
            <a href="profile.php?profile_username=<?php echo $userLoggedIn ?>">
                <?php echo $user['first_name']; ?>
            </a>
            <a href="#">
                <i class="far fa-envelope fa-lg"></i>
            </a>
            <a href="index.php">
                <i class="far fa-home fa-lg"></i>
            </a>
            <a href="#">
                <i class="far fa-bell fa-lg"></i>
            </a>
            <a href="#">
                <i class="far fa-users fa-lg"></i>
            </a>
            <a href="#">
                <i class="far fa-cog fa-lg"></i>
            </a>
            <a href="includes/handlers/logout.php">
                <i class="fas fa-sign-out-alt fa-lg"></i>
            </a>
        </nav>
    </div> <!-- top_bar -->

    <div class="wrapper">

