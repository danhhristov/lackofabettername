<?php
     require_once "config.php";
     session_start();
     $currCategory = $_SESSION["category"];
     $sql = "SELECT * FROM `QUESTION` WHERE `category` LIKE '$currCategory' ORDER BY `answer_id` DESC";
     $result = $conn->query($sql);
     if(!$result) {die("Query failed".$conn->connect_error);}
     $questions = array();
     $qCount=0;
     while($row = $result->fetch_assoc()) //for every question with "Biology" category
     {
         //Get the answers
         $answerID = $row['answer_id'];
         $question = $row['q_text'];
         //Take all the answers from the QAnswers table from the database
         $getAnswers = "SELECT * FROM `QANSWERS` WHERE `id` = $answerID";
         $getAnswersResult = $conn->query($getAnswers);
         if(!$getAnswersResult) {die("Query failed".$conn->connect_error);}
         if($answerRow = $getAnswersResult->fetch_assoc())
         {
            $answers = array();
            $answers = [$answerRow['option_1'], $answerRow['option_2'], $answerRow['option_3'], $answerRow['option_4']];
            $questions[$question] = $answers;
         }
         
     }
     foreach($questions as $qestion => $answers)
     {
        echo "<div class=\"qContainer\">";
        echo array_search($answers, $questions)."</br>";
        foreach($answers as $answer) 
        {
            echo "<button>" . $answer . "</button>";
        }
        echo "</div>";
     }
?>
<html>
    <head>
        <title>Questions</title>
    </head>
    <body>

    </body>
</html>


