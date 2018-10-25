<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

//declaring form variables
$fname = ""; //first name
$lname = ""; //last name
$em = ""; //email
$em2 = ""; //email2
$password = ""; //password
$password = ""; //password2
$date = ""; //user sign up date
$error_array = []; //holds error messages

if(isset($_POST['register_button'])) {

    //first name
    $fname = strip_tags($_POST['reg_fname']); //removes html tags
    $fname = str_replace(' ', '', $fname); //removes spaces
    $fname = ucfirst(strtolower($fname)); //uppercase name
    $_SESSION['reg_fname'] = $fname;

    //last name
    $lname = strip_tags($_POST['reg_lname']); //removes html tags
    $lname = str_replace(' ', '', $lname); //removes spaces
    $lname = ucfirst(strtolower($lname)); //uppercase name
    $_SESSION['reg_lname'] = $lname;

    //email
    $em = strip_tags($_POST['reg_email']); //removes html tags
    $em = str_replace(' ', '', $em); //removes spaces
    //$em = ucfirst(strtolower($em));
    $_SESSION['reg_email'] = $em;

    //email
    $em2 = strip_tags($_POST['reg_email2']); //removes html tags
    $em2 = str_replace(' ', '', $em2); //removes spaces
    //$em2 = ucfirst(strtolower($em2));
    $_SESSION['reg_email2'] = $em2;

    //password
    $password = strip_tags($_POST['reg_password']); //removes html tags
    $password2 = strip_tags($_POST['reg_password2']); //removes html tags

    $date = date("Y-m-d"); //gets current date

    //validate email
    if($em == $em2) {
        //check for valid email format since html does not catch .com endings
        if(filter_var($em, FILTER_VALIDATE_EMAIL)){
            $em = filter_var($em, FILTER_VALIDATE_EMAIL);

            //ckeck if email already exists
            $emCheck = mysqli_query($con, "SELECT email FROM users WHERE email='$em' ");
            //count number of rows returned
            $num_rows = mysqli_num_rows($emCheck);
            if($num_rows > 0) {
                //array_push($error_array, "Email is already in use");
                $error_array[]= "Email is already in use";
            }
            
        } else {
            $error_array[] = "Invalid email format"; //"Invalid email format";
        }

    } else {
        $error_array[]= "Emails do not match"; //"Emails do not match";
    }

    //validate name
    if(strlen($fname) < 2 || strlen($fname) > 25) {
        $error_array[]=  "Your first name must be between 2 and 25 characters";
    }
    if(strlen($lname) < 2 || strlen($lname) > 25) {
        $error_array[]=  "Your last name must be between 2 and 25 characters";
    }

    //validate password
    if($password != $password2) {
        $error_array[]=  "Your passwords do not match";
    } else {
        if(preg_match('/[^A-Za-z0-9]/', $password)){
            $error_array[]= "Password must contain numbers and characters only";
        }
    }
    if(strlen($password) < 5 || strlen($password) > 30) {
        $error_array[]= "Your password must be more than 5 and less than 30 characters";
    }
    
    //insert data
    if(empty($error_array)){
        $password = md5($password); //encrypt password
        $username = strtolower($fname."_".$lname);
        $usernameQuery = mysqli_query($con, "SELECT username FROM users WHERE username='$username' ");
        
        $i = 0;
        //if username exists, then create a new username with a number 
        while(mysqli_num_rows($usernameQuery) != 0){
            $i++;
            $username .= "_".$i;
            $usernameQuery = mysqli_query($con, "SELECT username FROM users WHERE username='$username' ");
        }

        //profile picture
        $rand = rand(1, 2); //random of 2 numbers , 1 and 2
        switch($rand) {
            case 1:
                $profilePic = "assets/images/profile-pics/defaults/man_blue.png";
                break;
            case 2:
                $profilePic = "assets/images/profile-pics/defaults/man_white.png";
                break;
        }
        
        $query = mysqli_query($con, "INSERT INTO users VALUES (NULL, '$fname', '$lname', '$username', '$em', '$password', '$date', '$profilePic', '0', '0', 'no', ',' )" );
        
        $error_array[]= "Registration is successful. Go ahead and login.";

        //clear registration variables after successful registration
        $_SESSION['reg_fname']= " ";
        $_SESSION['reg_lname']= " ";
        $_SESSION['reg_email']= " ";
        $_SESSION['reg_email2']= " ";
    }
}

?>