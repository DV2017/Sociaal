<?php

ob_start(); //turns on output buffer -- saves all the php data
session_start(); //allows to store variables into session variables


$con = mysqli_connect("localhost", "root", "C1RT4Nmysql", "social");

if (mysqli_connect_error()){
    echo "Failed to connect." . mysqli_connect_error();
}

mysqli_set_charset($con, 'utf8');

?>


