<?php

require_once 'config.php';
session_start();

$getUsers = "SELECT * FROM `USER` WHERE 1";
$users = $conn->query($getUsers);

while($user = $users->fetch_assoc()){
    $uScore = 0;
    $getUAnswers = "SELECT * FROM `UANSWERS` WHERE user_id = '".$user["id"]."'";
    $uAnswers = $conn->query($getUAnswers);
    if($uAnswers->num_rows == 0){continue;}
    while($uAnswer = $uAnswers->fetch_assoc()){
        if($uAnswer["isCorrect"] == 1){
            $uScore++;
        }
    }
    $updateScoresql = "UPDATE `USER` SET `score` = '$uScore' WHERE `USER`.`id` = '".$user["id"]."'";
    if(!($conn->query($updateScoresql) === TRUE)){
       die("Update failed".$conn->error);
    }
}


?>