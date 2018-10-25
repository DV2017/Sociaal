<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

class Post {
    private $con;
    private $userObject;
    
    public function __construct($con, $user) {
        $this->con = $con;
        $this->userObject = new User($this->con, $user);
        
    }
    
    public function submitPost($body, $user_to) {
        /* 
        * strips html tags 
        * pushes the text into create a legal SQL string 
        * that you can use in an SQL statement
        * the connection variables checks for an active connection
        * returns the escaped string; empty string if no connection
        */
        $body = strip_tags($body);
        $body = mysqli_real_escape_string($this->con, $body);

        //only a check to see if string is empty
        $is_empty = preg_replace('/\s+/', '', $body);
        //post text only if not empty
        if($is_empty != "") {
            //current date and time
            $date = date("Y-m-d H:i:s");
            $added_by = $this->userObject->getUsername();
            //if is own profile, then there is no user_to
            if($user_to == $added_by) {
                $user_to = "none";
            }

            //insert post to db
            $query = mysqli_query($this->con, "INSERT INTO posts VALUES(NULL, '$body', '$added_by', '$user_to', '$date', 'no', 'no', '0') ");

            //store the id variable - use for later
            //$returned_id = mysqli_insert_id($this->con);

            // increment number of posts by user and update user database
            $numPosts = $this->userObject->getNumPosts();
            $numPosts++;
            $query = mysqli_query($this->con, "UPDATE users SET num_posts='$numPosts' WHERE username='$added_by' " );
        }
        else {
            echo "Nothing to post";
        }

    }

    public function loadPostsFriends($data, $limit) {
        $page = $data['page'];
        $userLoggedIn = $this->userObject->getUsername();
        if($page == 1) 
            $start = 0;
        else
            $start = ($page - 1) * $limit;

        //already a connection variable and a new user is created based on userLoggedIn.
        $str = ""; //string to return

        //take all posts which are not deleted
        $query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC ");
        if(mysqli_num_rows($query) > 0) {
            $num_iterations = 0;
            $count = 1;

            while($row = mysqli_fetch_array($query)) {
                $id = $row['id'];
                $body = $row['body'];
                $added_by = $row['added_by'];
                $user_to = $row['user_to'];
                $date_added = date_format(date_create($row['date_added']), "d-M-Y");

                //prepare user_to string so it can be included even if not posted to another user
                if($user_to == "none") {
                    $user_to = "";
                } else {
                    $user_to_Obj = new User($this->con, $user_to);
                    $user_to_name = $user_to_Obj->getFirstAndLastName();
                    $user_to = "to <a href='profile.php?profile_username=".$user_to."'>".$user_to_name."</a>"; 
                }

                //dont include posts by users if account is closed
                $added_by_Obj = new User($this->con, $added_by);
                if($added_by_Obj->isClosed()){
                    continue;
                }

                //limit posts to friends of userLogeedIn only: check the isFriend() function in User class
                if($this->userObject->isFriend($added_by)) {

                    if($num_iterations++ < $start)
                        continue; 
                    //this repeats the iteration until the last-loaded record is reached

                    if($count > $limit)
                        break;
                    else $count++;

                    //splitting the php code to insert toggle function in JS script 
    ?>

    <script>
     //same toggle function as in comment_frame.php with some changes
     //toggle comments based on post id queried earlier in line 72 or so.
     //function name is linked to the id: toggle13 etc
     function toggle<?php echo $id; ?>() {
         /*binding the click event to a variable and check if that was NOT an anchor tag because the a-tag has a different destination.*/
         var target = $(event.target);
        if(!target.is("a")){
            var elementHTML = document.getElementById("toggleComment<?php echo $id; ?>");
            if(elementHTML.style.display == "block")
                elementHTML.style.display = "none";
            else 
                elementHTML.style.display = "block";
        } 
        
    }
    </script>                

    <?php
                    //php script continues
                    //count comments for each post
                    $comments_query = mysqli_query($this->con, "SELECT id FROM comments WHERE post_id='$id' ");
                    $comments_num = mysqli_num_rows($comments_query);

                    //note that iframe is used to embed comments-frame as a separate page
                    $added_by_name = $added_by_Obj->getFirstAndLastName();
                    $profile_pic = $added_by_Obj->getProfilePic();
                    $added_by = "<a href='profile.php?profile_username=".$added_by."'>".$added_by_name."</a>";

                    
                    $str .= "<div class='status_post' >
                                <div class='post_profile_pic'><img src='$profile_pic' width='50'></div>
                                <div class='posted_by'>$added_by $user_to &nbsp;&nbsp;&nbsp;&nbsp; $date_added </div>
                                <br>
                                <div id='post_body'>$body</div>
                                <br>
                            </div>
                            <div class='newsFeedPostOptions'>
                                <div class='comment_count' onclick='toggle$id()'>Comments($comments_num)</div>
                                &nbsp;&nbsp;&nbsp;

                            </div>
                            <div class='post-comment' id='toggleComment$id' style='display:none;'>
                                <iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
                            </div> 
                            <hr>"; 

                } //ends isFriend() function call so that posts by only friends are shown
            } //while loop ends

            if($count > $limit)
                $str .= "<input type='hidden' class='nextPage' value='".($page + 1)."'>
                        <input type='hidden' class='noMorePosts' value='false'>";
            else $str .= "<input type='hidden' class='noMorePosts' value='true'>
                         <p style='text-align: center;'>No more Posts</p>";

        } //end of if statement
        echo $str; 
    }    
}

?>