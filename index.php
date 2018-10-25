<?php 
include("includes/header.php"); 
include("includes/classes/User.php");
include("includes/classes/Post.php");
//this allows all the user data to be made available to this page
ini_set("display_errors", 1);
error_reporting(E_ALL);

if(isset($_POST['post_button'])) {
    $post = new Post($con, $userLoggedIn);
    $post->submitPost($_POST['post_text'], "none");
}
?>
    <div class="user_details column">
        <a href="#"> <img src="<?php echo $user['profile_pic']; ?>"> </a>

        <div class="user_details_left_right">
            <a href="#"> <?php echo $user['first_name'] . " " . $user['last_name'] ;?> </a>
            <br>
            <?php 
                echo "Posts: " . $user['num_posts']. "<br>";
                echo "Likes: " . $user['num_likes'];
            ?>
        </div> <!-- user_details_left_right -->
    </div> <!-- user_details -->

    <div class="main_column column">
        <form class="post_form" action="index.php" method="post">
            <textarea name="post_text" id="post_text" cols="30" rows="4" placeholder="Got something to say?"></textarea>
            <input type="submit" name="post_button" id="post_button" value="Post">
            <hr>
        </form>

        <div class="posts_area"></div>
        <img id="loading" src="assets/images/icons/spinner-page-loader.gif" alt="page loading image">

    </div> <!-- main_column column -->

<script>
    var userLoggedIn = "<?php echo $userLoggedIn;?>";
    $(document).ready(function(){
        $('#loading').show();
        
        //ajax request to load all posts with a prescribed limit
        $.ajax({
            url: 'includes/handlers/ajax_load_posts.php',
            type: 'POST',
            data: 'page=1&userLoggedIn=' + userLoggedIn,
            cache: false,

            success: function(data){
                $('#loading').hide();
                $('.posts_area').html(data);
            }
        }); //end of ajax call

        $(window).scroll(function(){
            var height = $('.posts_area').height(); //div containig posts
            var scroll_top = $(this).scrollTop();
            var page = $('.posts_area').find('.nextPage').val();
            var noMorePosts = $('.posts_area').find('.noMorePosts').val();
                        
            //document.body.scrollHeight is height of the body's content
            if((document.body.scrollHeight == window.innerHeight + document.body.scrollTop) && noMorePosts == 'false'){
                $('#loading').show();

                var ajaxRequest = $.ajax({
                    url: 'includes/handlers/ajax_load_posts.php',
                    type: 'POST',
                    data: 'page=' + page + '&userLoggedIn=' + userLoggedIn,
                    cache: false,
                    
                    success: function(response) {
                        $('.posts_area').find('.nextPage').remove(); //remove nextPage
                        $('.posts_area').find('.noMorePosts').remove();//remove noMorePosts
                        $('#loading').hide();
                        $('.posts_area').append(response);
                    }
                }); //end ajax call

            } //if statement end
            else {
                return false;
            } 
        }); //(window).scroll(function()
    }); //(document).ready(function()
</script>

</div> <!--wrapper from header.php -->
</body>
</html>