
<html lang="en">
<head>
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php
    include 'config/config.php';
    include("includes/classes/User.php");
    include("includes/classes/Post.php");

    //if user who wants to like it is not logged in, then he needs to be logged in to like it
    if(isset($_SESSION['username'])) {
        //userLoggedIn is a session varaiable corresponding to the logged in user
        $userLoggedIn = $_SESSION['username'];
        //$firstname = $_SESSION['first_name'];
        $query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn' ");
        $user = mysqli_fetch_array($query);
    } else {
        header("Location: register.php");
    }

    //GET post ID
    if(isset($_GET['post_id'])){
        $post_id = $_GET['post_id'];
    }


?>

</body>
</html>