<?php
require 'config/config.php';
require 'includes/handlers/register_handler.php';
require 'includes/handlers/login_handler.php';

ini_set("display_errors", 1);
error_reporting(E_ALL);

function setInputValue($value){
    if(isset($_POST[$value])){
        echo $_POST[$value];
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Social - register</title>

    <link rel="stylesheet" href="assets/css/register.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>

</head>
<body>

<?php
if(isset($_POST['register_button'])){
    echo '
    <script>
        $(document).ready(function(){
            $("#first").hide();
            $("#second").show();
        });
    </script>
    ';
}
?>


<!-- LOGIN -->
<div class="wrapper">
    <div class="login_box">
        <div class="login_header">
            <h1>COMMUNITY</h1>
            Login
        </div>

        <div id="first">
            <form action="register.php" id="login" method="post">
                <input type="email" name="loginEmail" placeholder="Email Address" value="<?php if(isset($_SESSION['loginEmail'])) echo $_SESSION['loginEmail'];?>" ><br>
                <input type="password" name="loginPassword" placeholder="Password"><br>
                <?php if(in_array("Wrong email or password.", $error_array)) echo "Wrong email or password.<br>";?>            
                <input type="submit" name="login_button" value="Login"><br>
                <a href="#" id="signup" class="signup">New to Community? Sign up here !</a>
            </form>
        </div> <!-- first -->


        <!-- REGISTER -->
        <div id="second">
            <form action="register.php" id="register" method="POST">
                <input type="text" name="reg_fname" placeholder="first name" value="<?php setInputValue('reg_fname') ?>" required><br>
                <?php if(in_array("Your first name must be between 2 and 25 characters", $error_array)) echo "Your first name must be between 2 and 25 characters<br>";?>
                
                <input type="text" name="reg_lname" placeholder="last name" value="<?php setInputValue('reg_lname') ?>" required><br>
                <?php if(in_array("Your last name must be between 2 and 25 characters", $error_array)) echo "Your last name must be between 2 and 25 characters<br>";?>

                <input type="email" name="reg_email" placeholder="Email" value="<?php setInputValue('reg_email') ?>" required><br>

                <input type="email" name="reg_email2" placeholder="Confirm email" value="<?php setInputValue('reg_email2') ?>" required><br>
                
                <?php if(in_array("Email is already in use", $error_array)) echo "Email is already in use<br>";
                    if(in_array("Invalid email format", $error_array)) echo "Invalid email format<br>";
                    if(in_array("Emails do not match", $error_array)) echo "Emails do not match<br>";
                ?>

                <input type="password" name="reg_password" placeholder="Password" required><br>

                <input type="password" name="reg_password2" placeholder="Confirm password" required><br>
                <?php if(in_array("Your passwords do not match", $error_array)) echo "Your passwords do not match<br>";
                    if(in_array("Password must contain numbers and characters only", $error_array)) echo "Password must contain numbers and characters only<br>";
                    if(in_array("Your password must be more than 5 and less than 30 characters", $error_array)) echo "Your password must be more than 5 and less than 30 characters<br>";
                ?>
                <input type="submit" name="register_button" value="Register">
                <br>
                <?php if(in_array("Registration is successful. Go ahead and login.", $error_array)) echo "Registration is successful. Go ahead and login.<br>"; 
                ?>
                <a href="#" id="signin" class="signin">Already have an account? Sign in here !</a>
            </form>
        </div> <!-- second -->
        
    </div> <!-- login-box -->
</div> <!-- wrapper -->
</body>
</html>
