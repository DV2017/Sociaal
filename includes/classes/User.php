<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

class User {
    private $con;
    private $user;
    
    public function __construct($con, $user) {
        $user_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user' ");
        $this->user = mysqli_fetch_array($user_query);
        $this->con = $con;
    }

    public function getUsername() {
        return $this->user['username'];
    }
    
    public function getNumPosts() {
        return $this->user['num_posts'];
    }

    public function getProfilePic(){
        return $this->user['profile_pic'];
    }

    public function getFirstAndLastName() {
        $username = $this->user['username'];
        $query = mysqli_query($this->con, "SELECT first_name, last_name FROM users WHERE username='$username' ");
        $row = mysqli_fetch_array($query);
        return $row['first_name']. " " .$row['last_name']; 
    }
    
    public function isClosed() {
        $username = $this->user['username'];
        $query = mysqli_query($this->con, "SELECT user_closed FROM users WHERE username='$username' ");
        $row = mysqli_fetch_array($query);
        
        if($row['user_closed'] == "yes")
            return true;
        else
            return false; 
    }

    public function isFriend($username_to_check){
        $usernameComma = ",".$username_to_check.",";
        //in post, 'added_by' is $username_to_check
        // is the person posting is a friend or is the user himself
        if(strstr($this->user['friend_array'], $usernameComma) || $username_to_check == $this->user['username']) 
            return true;
        else 
            return false;
    }
}

?>