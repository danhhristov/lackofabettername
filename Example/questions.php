<?php

require_once "config.php";

session_start();

class Answer
{
    public $id;
    public $a1;
    public $a2;
    public $a3;
    public $a4;
    public $correct;
    
     function getCorrect($ans) 
    {
        if ($ans == 1) {
            return $this->a1;
        }
        if ($ans == 2) {
            return $this->a2;
        }
        if ($ans == 3) {
            return $this->a3;
        }
        if ($ans == 4) {
            return $this->a4;
        }
    }
}

class userAnswer 
{
    public $q_id;
    public $u_answered;
}

    $answeredQuestions;
    $questionsToAnswer;
    $answers;
    $answersArray = [];
    $userAnswersArray = [];
    
    
    $currCategory = ($_GET['category']);
    $_SESSION["category"] = $currCategory;
        
    $userId = 0;
    
    if(isset($_SESSION["username"]))
        {
            $username = $_SESSION["username"];
            
            $sql = "SELECT * FROM `USER` WHERE `username` LIKE '$username'";
            $result = $conn->query($sql);
            if(!$result) {die("Query failed".$conn->connect_error);}
            $row=$result->fetch_assoc();
            $userId = $row["id"];
            
            $getNotAnsweredQuestions = "SELECT * FROM `QUESTION` WHERE category LIKE '$currCategory' AND id NOT IN"
                    . "(SELECT q_id FROM `UANSWERS` WHERE user_id = '$userId')";
            
            $getAnsweredQuestions = "SELECT * FROM `QUESTION` WHERE category LIKE '$currCategory' AND id IN"
                    . "(SELECT q_id FROM `UANSWERS` WHERE user_id = '$userId')";
            
            $answeredQuestions =  $conn->query($getAnsweredQuestions);
            $questionsToAnswer = $conn->query($getNotAnsweredQuestions);
            
            $getAnswers = "SELECT * FROM `QANSWERS`";
            $answers = $conn->query($getAnswers);
            
            while($answerRow = $answers->fetch_assoc()) //get answers
            {
                $answerObject = new Answer();
                $answerObject->id = $answerRow['id'];
                $answerObject->a1 = $answerRow['option_1'];
                $answerObject->a2 = $answerRow['option_2'];
                $answerObject->a3 = $answerRow['option_3'];
                $answerObject->a4 = $answerRow['option_4'];
                $answerObject->correct = $answerRow['correct'];
                \array_push($answersArray, $answerObject);
            }
            
            $queryUserAnswerOptions = "SELECT * FROM `UANSWERS` WHERE user_id = $userId";
            $getUserAnswerOptions = $conn->query($queryUserAnswerOptions);
            while($aOptionRow = $getUserAnswerOptions->fetch_assoc()) //get answers
            {
                $userAnswerObject = new userAnswer();
                $userAnswerObject->q_id = $aOptionRow['q_id'];
                $userAnswerObject->u_answered = $aOptionRow['u_answered'];
                \array_push($userAnswersArray, $userAnswerObject);
            }
        }
        else{
            header("Location:login.php");
        }
    
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Questions</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <script>
            function updateQuestions(ans) {
                    if (window.XMLHttpRequest) {
                      // code for IE7+, Firefox, Chrome, Opera, Safari
                      xmlhttp=new XMLHttpRequest();
                    } else { // code for IE6, IE5
                      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                     xmlhttp.onreadystatechange=function() {
                          document.getElementById("data").innerHTML=this.responseText;
                    };
                    
                    xmlhttp.open("GET","dynamicFetcher.php?q="+ans,true);
                    xmlhttp.send();
                     
             }                   
             
             function getAnswerId(clicked_id)
             {
                 if (document.getElementById(clicked_id + 'a1').checked) {
                        rate_value = clicked_id + 'a1';
                      }
                 if (document.getElementById(clicked_id + 'a2').checked) {
                        rate_value = clicked_id + 'a2';
                      }
                 if (document.getElementById(clicked_id + 'a3').checked) {
                        rate_value = clicked_id + 'a3';
                      }
                 if (document.getElementById(clicked_id + 'a4').checked) {
                        rate_value = clicked_id + 'a4';
                      }
                  return rate_value;
             }
             
        </script>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <?php
        require_once 'frame.php';
        ?>
        <div id="data">
            
            <div class="questions-wrapper">
            <h2>Questions</h2>
        <?php
            
            echo "<table class=\"table-questions\">";
            $counter=1;
            while($row = $questionsToAnswer->fetch_assoc())
                  { /* style=\"border: 3px solid red\"*/
                     echo "<tr><td class=\"question-text\" colspan=\"2\">$counter. ".$row['q_text']."</td></tr>";
                     $counter++;
                     for($i=0; $i<count($answersArray); $i++) //get answers                                {
                          {
                              if($row['answer_id']==$answersArray[$i]->id)
                                  {
                                     echo "<tr><td class=\"options-radio\"><input type='radio' id=".$answersArray[$i]->id."a1 name='option' value=1></td><td class=\"ans-options\">".$answersArray[$i]->a1."</td></tr>";
                                     echo "<tr><td class=\"options-radio\"><input type='radio' id=".$answersArray[$i]->id."a2 name='option' value=2></td><td class=\"ans-options\">".$answersArray[$i]->a2."</td></tr>";
                                     echo "<tr><td class=\"options-radio\"><input type='radio' id=".$answersArray[$i]->id."a3 name='option' value=3></td><td class=\"ans-options\">".$answersArray[$i]->a3."</td></tr>";
                                     echo "<tr><td class=\"options-radio\"><input type='radio' id=".$answersArray[$i]->id."a4 name='option' value=4></td><td class=\"ans-options\">".$answersArray[$i]->a4."</td></tr>";
                                     echo "<tr><td></td><td><button class=\"submit-answer\" type='button' id=".$answersArray[$i]->id." onClick='updateQuestions(getAnswerId(this.id))'> Submit </button> </td></tr>";   
                                  }
                          }
                  }
            echo "</table>";
            echo "</div>";

            echo "<div class=\"answers-wrapper\">";
            echo "<h2>Answers</h2>";

            echo "<table class=\"table-answers\">";                  
            $counter2= 1;
            while($row = $answeredQuestions->fetch_assoc())
                  {
                     echo "<tr><td class=\"question-text\" colspan=\"2\">$counter2. ".$row['q_text']."</td></tr>";
                     $counter2++;

                                                 $uAnswered = 0;
                        for($i=0; $i<count($userAnswersArray); $i++) //get answers                                {
                          {
                              if($row['id']==$userAnswersArray[$i]->q_id)
                              {
                                  $uAnswered = $userAnswersArray[$i]->u_answered;
                              }
                          }


                     for($i=0; $i<count($answersArray); $i++) //get answers                                {
                          {
                              if($row['answer_id']==$answersArray[$i]->id)
                                  {
                                     if($answersArray[$i]->getCorrect($answersArray[$i]->correct)===$answersArray[$i]->getCorrect($uAnswered))
                                     {
                                     echo "<tr><td class=\"options-radio\"><input type='radio' name=".$answersArray[$i]->id." value=1 checked></td><td class=\"ans-options\">".$answersArray[$i]->getCorrect($uAnswered)." : <label style='color:green'>Correct</label></td></tr>";
                                     //echo "<td style=\"color: green;\">Correct!</td>";
                                     }
                                     else
                                     {
                                      echo "<tr><td class=\"options-radio\"><input type='radio' name=".$answersArray[$i]->id." value=1 disabled></td><td class=\"ans-options\">".$answersArray[$i]->getCorrect($answersArray[$i]->correct)." : <label style='color:green'>Correct</label></td></tr>";
                                      echo "<tr><td class=\"options-radio\"><input type='radio' name=".$answersArray[$i]->id." value=1 checked></td><td class=\"ans-options\">".$answersArray[$i]->getCorrect($uAnswered)." : <label style='color:red'>Incorrect</label></td></tr>";
                                      //echo "<td style=\"color: red;\">Incorrect!</td>";
                                     }
                                  }
                          }
                  }
            echo "</table>";
            echo "</div>";
                
        ?>
        
    </div>
    </body>
</html>