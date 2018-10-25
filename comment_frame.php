<?php
    include 'config/config.php';
    include("includes/classes/User.php");
    include("includes/classes/Post.php");

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

<html>
<head>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background-color: #ffcfcf;">

    <script>
        function toggle(){
            //to show comment_section
            var element = document.getElementById("comment_section");

            if(element.style.display == "block"){
                element.style.display = "none";
            }else{
                element.style.display = "block";
            }
        }
    </script>

    <?php
    //get post_id of the post to which comments are being posted
    if(isset($_GET['post_id'])){
        $post_id = $_GET['post_id'];
    }

    $user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id='$post_id' ");
    $row = mysqli_fetch_array($user_query);

    //comments are made to posts added by somebody
    $posted_to = $row['added_by'];

    //insert to database when comments form is activated
    if(isset($_POST['postComment'.$post_id])){
        $post_body = $_POST['post_body'];
        $post_body = mysqli_real_escape_string($con, $post_body);
        $date_time_now = date("Y-m-d H:i:s");

        $insert_post = mysqli_query($con, "INSERT INTO comments VALUES (NULL, '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id') ");
        
        echo '<em>Comment Posted.</em>';
        //this comment is loaded before the form
    }
    ?>

   <!---creates the comment frame to send post_id to ajax handler-->
   <!-- name is the id of the post to which comments are posted -->
   <form action="comment_frame.php?post_id=<?php echo $post_id;?>" id="comment_form" name="postComment<?php echo $post_id;?>" method="post">
    <textarea name="post_body"></textarea>
    <button name="postComment<?php echo $post_id;?>" value="Post">POST</button>
   </form> 

   <!---load comments -->

   <?php
    $get_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id DESC");
    $count = mysqli_num_rows($get_comments);
    if($count !=0) {
        while($comment = mysqli_fetch_array($get_comments)){
            //pull one comment at a time
            $comment_body = $comment['post_body'];
            $posted_to = $comment['posted_to'];
            $posted_by = $comment['posted_by'];
            $date_added = $comment['date_added'];
            $removed = $comment['removed'];
            $date_added = date_format(date_create($date_added), "d-M-Y H:i");

            //add posted by name and pic reference it to profile page
            $user_Obj = new User($con, $posted_by);   
        
        //break php to insert the comment section for each comment in while loop
        ?>

        <!--this is where each comment for that post id will be posted-->
        <!--because its a iframe load, any links have to be outside the iframe-->
        <div class="comment_section">
            <a href="<?php echo "profile.php?profile_username=".$posted_by; ?>" target="parent">
            <img src="<?php echo $user_Obj->getProfilePic();?>" title="<?php echo $posted_by; ?>"alt="" >
            </a>
            <div class="commented_by"> 
                <a href="<?php echo "profile.php?profile_username=".$posted_by; ?>" target="parent">
                <b><?php echo $user_Obj->getFirstAndLastName(); ?></b>
                </a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo $date_added; ?>
                <br>

                <div class="comment_body"><?php echo $comment_body; ?></div>
                <hr>
            </div>
            <br>
        </div>

        <?php
        }//end of while statement
    } //end of if statement
    else {
        echo "<em>No comments to show.</em>";
    }
   ?>

    

</body>
</html>