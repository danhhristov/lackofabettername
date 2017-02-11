<?php

$servername = "devweb2016.cis.strath.ac.uk";
$username = "cs312q";
$database = "cs312q";
$pass = "eghoo5aewohP";

function hashP($message){
    return hash('ripemd160', $message);
}

$categories = ["Biology", "Chemistry", "Mathematics", "History", "Geography", "Computing", "Physics"];

$conn = new mysqli($servername, $username, $pass, $database);

if($conn->connect_error){
    die("Connection failed : ".$conn->connect_error);
}
function safePost($conn,$name){
        if(isset($_POST[$name])){
            return $conn->real_escape_string(strip_tags($_POST[$name]));
        } else { return "";}
    }
    ?>