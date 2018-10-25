<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

if(isset($_POST['login_button'])){
    $email = filter_var($_POST['loginEmail'], FILTER_SANITIZE_EMAIL);
    $_SESSION['loginEmail'] = $email;

    $password = md5($_POST['loginPassword']);

    $checkDB = mysqli_query($con, "SELECT * FROM users WHERE email = '$email' AND password = '$password' ");
    $result = mysqli_num_rows($checkDB);
    
    if($result == 1) {
        $row = mysqli_fetch_array($checkDB);
        $username = $row['username'];
        $firstname = $row['first_name'];

        mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email' ");

        $_SESSION['username'] = $username;
        $_SESSION['first_name'] = $firstname;
        
        header("Location: index.php");
        
    } else {
        $error_array[] = "Wrong email or password.";
    }  
}

?>