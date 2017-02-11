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

    $fetchedAnswer = ($_GET['q']);
    $answerInfo = explode("a", $fetchedAnswer);
    
    $answeredQuestions;
    $questionsToAnswer;
    $answers;
    $answersArray = [];
    $userAnswersArray = [];
    
    
    $currCategory = $_SESSION["category"];
    
    $userId = 0;
    
    if(isset($_SESSION["username"]))
        {
            $username = $_SESSION["username"];
            $pagePermission = 1;
            
            $sql = "SELECT * FROM `USER` WHERE `username` LIKE '$username'";
            $result = $conn->query($sql);
            if(!$result) {die("Query failed".$conn->connect_error);}
            $row=$result->fetch_assoc();
            $userId = $row["id"];
            $userScore = $row["score"];
            
            $answeredQ = "SELECT * FROM `QUESTION` WHERE answer_id = $answerInfo[0]";
            $answeredQresult = $conn->query($answeredQ);
            $fetchAnsweredQ = $answeredQresult->fetch_assoc();
            $qId = $fetchAnsweredQ["id"];
            
            
            $insertIntoAnswers = "INSERT INTO `UANSWERS`(`user_id`, `q_id`, `u_answered`, `rating`, `isCorrect`) "
            . "VALUES ($userId, $qId, $answerInfo[1], 0, 0)";
            $conn->query($insertIntoAnswers);
            
            $getNotAnsweredQuestions = "SELECT * FROM `QUESTION` WHERE category LIKE '$currCategory' AND  id NOT IN"
                    . "(SELECT q_id FROM `UANSWERS` WHERE user_id = '$userId')";
            
            $getAnsweredQuestions = "SELECT * FROM `QUESTION` WHERE category LIKE '$currCategory' AND  id IN"
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
            
            $queryUserAnswerOptions = "SELECT * FROM `UANSWERS` WHERE user_id = '$userId'";
            $getUserAnswerOptions = $conn->query($queryUserAnswerOptions);
            while($aOptionRow = $getUserAnswerOptions->fetch_assoc()) //get answers
            {
                $a_id = "SELECT * FROM `QUESTION` WHERE id LIKE '".$aOptionRow["q_id"]."'";
                $q_a_id = $conn->query($a_id);
                if($q = $q_a_id->fetch_assoc())
                {
                    $corr = "SELECT * FROM `QANSWERS` WHERE id LIKE '".$q["answer_id"]."'";
                    $corrResult = $conn->query($corr);
                    if($corrRow = $corrResult->fetch_assoc())
                    {
                        if($corrRow["correct"] == $aOptionRow["u_answered"])
                        {
                            $sql1 = "UPDATE `UANSWERS` SET isCorrect= '1' WHERE q_id LIKE '".$aOptionRow["q_id"]."'";
                            $result = $conn->query($sql1);
                            
                        }
                    }
                    
                }
            }
            
            $queryUserAnswerOptions = "SELECT * FROM `UANSWERS` WHERE user_id = $userId";
            $getUserAnswerOptions = $conn->query($queryUserAnswerOptions);
            while($aOptionRow = $getUserAnswerOptions->fetch_assoc()) //get answers
            {
                $userAnswerObject = new userAnswer();
                $userAnswerObject->q_id = $aOptionRow['q_id'];
                $userAnswerObject->u_answered = $aOptionRow['u_answered'];
                \array_push($userAnswersArray, $userAnswerObject);
            
                $correctAnswer = $answerObject->correct;
                $userAnswer    = $userAnswerObject->u_answered;
                $uanid = $aOptionRow['id'];
                
            }
        }
        else{
            header("Location: login.php");
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
        
    </head>
    <body>
        <div id="data">
            <div class="questions-wrapper">
            <h2>Questions</h2>
        <?php
           
            echo "<table class=\"table-questions\">";                  
            $counter=1;
            while($row = $questionsToAnswer->fetch_assoc())
                  {
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
                                     echo "<tr><td class=\"options-radio\"><input type='radio' name=".$answersArray[$i]->id." value=1 checked></td><td class=\"ans-options\"> ".$answersArray[$i]->getCorrect($uAnswered)." : <label style='color:green'>Correct</label></td></tr>";
                                     }
                                     else
                                     {
                                      echo "<tr><td class=\"options-radio\"><input type='radio' name=".$answersArray[$i]->id." value=1 disabled></td><td class=\"ans-options\"> ".$answersArray[$i]->getCorrect($answersArray[$i]->correct)." : <label style='color:green'>Correct</label></td></tr>";
                                      echo "<tr><td class=\"options-radio\"><input type='radio' name=".$answersArray[$i]->id." value=1 checked></td><td class=\"ans-options\"> ".$answersArray[$i]->getCorrect($uAnswered)." : <label style='color:red'>Incorrect</label></td></tr>";
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