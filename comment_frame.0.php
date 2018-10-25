<?php
    //copy from header.php and pasted. 
    //error: if I place this in the body section
    require 'config/config.php';
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
    <title></title>
    <link rel="stylesheet" href="assets/css/style.css"> 
</head>
<body>


<script>
//toggling viewing of the iFrame
    function toggle() {
        var elementHTML = document.getElementById("comment_section");
        if(elementHTML.style.display == "block")
            elementHTML.style.display = "none";
        else 
            elementHTML.style.display = "block";
    }
</script>

<?php
//inserting comments
    //get id of post
    if(isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];
    }

    $post_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id='$post_id' ");
    $row = mysqli_fetch_array($post_query);
    $posted_to = $row['added_by'];
    $posted_by = $userLoggedIn; //the person who is logged in to comment
    
    if(isset($_POST['postComment'.$post_id])){
        //check if comments are posted by form name 
        $post_body = $_POST['post_body']; //from comment-form textarea
        $post_body = mysqli_real_escape_string($con, $post_body);
        $date_commented = date("Y-m-d H:i:s");
        $insertComment = mysqli_query($con, "INSERT INTO comments VALUES (NULL, '$post_body', '$userLoggedIn', '$posted_to', '$date_commented', 'no', '$post_id' )");
        echo "<p>Comment posted!</p>";
    } 
?>

<!-- Insert comments -->
<form action="comment_frame.php?post_id=<?php echo $post_id;?>" id="comment_form" name="postComment<?php echo $post_id;?>" method="POST">
    <textarea name="post_body"></textarea>
    <input type="submit" name="postComment<?php echo $post_id;?>" value="Post">
</form>

<!--Load comments -->
<?php
//profile pic, posted by, all posts for that post_id, date when posted
//get data from comments; get profile pic of posted by
    $get_comments_query = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id ASC");
    $count = mysqli_num_rows($get_comments_query);

    if($count != 0) {     
        while($row = mysqli_fetch_array($get_comments_query)) {
            $comment_body = $row['post_body'];
            $posted_by = $row['posted_by'];
            $posted_to = $row['posted_to'];
            $post_removed = $row['removed'];
            $date_added = date_format(date_create($row['date_added']), "d-M-Y");

            $user_obj = new User($con, $posted_by);
            $user_profile_pic = $user_obj->getProfilePic();
            $posted_by_name = $user_obj->getFirstAndLastName();

        }           
            /*
            echo "
                <div class='comment_section'>
                    <a href='profile.php?profile_username=".$posted_by."' target='_parent'>
                        <img src='$user_profile_pic' style='float:left;'>
                    </a>
                    <a href='profile.php?profile_username=".$posted_by."' target='_parent'>$posted_by_name</a>
                    &nbsp;&nbsp;&nbsp;&nbsp;$date_added<br>$comment_body
                </div>";            
            */

    } else {
        echo "There are no comments for this post";
    }
?>

<div class="comment_section">
    <a href="profile.php?profile_username=<?php echo $posted_by; ?>" target="_parent">
        <img src="<?php echo $user_profile_pic; ?>" title="<?php echo $posted_by; ?>" style="float:left;" height="30">
    </a>
    <a href="profile.php?profile_username=<?php echo $posted_by; ?>" target="_parent"><b> <?php echo $posted_by_name;?></b>
    </a>
    &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $date_added; ?> <br> <?php echo $comment_body; ?>
</div>


</body>
</html>