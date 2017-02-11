<?php
require_once 'config.php';
session_start();
if(isset($_SESSION['username'])){
    $A = safePost($conn, "option1");
    $B = safePost($conn, "option2");
    $C = safePost($conn, "option3");
    $D = safePost($conn, "option4");
    $category = safePost($conn, "category");
    $correct = safePost($conn, "correctAnswer");
    $question = safePost($conn, "question");
    $feedback = "";

    if(!($A == "" && $B == "" && $C == "" && $D == "")){
        $addQAnswers = "INSERT INTO `QANSWERS` (`id`, `option_1`, `option_2`, `option_3`, `option_4`, `correct`) VALUES (NULL, '$A', '$B', '$C', '$D', '$correct');";
        if(!($conn->query($addQAnswers) === TRUE)){
           die("Update failed".$conn->error);
        }
        $getAnswerID = "SELECT max(`id`) FROM QANSWERS WHERE 1";
        $result = $conn->query($getAnswerID);
        $row = $result->fetch_assoc();
        $q_id = $row["max(`id`)"];
        $addQ = "INSERT INTO `QUESTION` (`id`, `q_text`, `answer_id`, `category`, `rating`) VALUES (NULL, '$question', '$q_id', '$category', '0')";
        if(!($conn->query($addQ) === TRUE)){
           die("Update failed".$conn->error);
        }
        $feedback = "Thank you for adding a question!";
     }
     else{
         $feedback = "Please, provide text for each field!";

     }
}
else{
    header("Location: login.php");
}
?>
<html>
    <head>
        <title>Add Question</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <?php
        require_once 'frame.php';
        ?>
        <div id="addContainer">
            <div>
                <form method="post">
                    
                    <span id="info"><p><?php echo $feedback;?></p></span>
                    <div class="selectDiv">
                        Category: <select name="category">
                        <?php
                        foreach($categories as $c) {
                            echo "<option value=\"".$c."\">".$c."</option>\n";
                        }
                        ?>
                        </select>
                    </div>

                    <p class="addQ">Question text:</p>
                    <input class="qInput" type="text" name="question"/>
                    <p class="addQ">Answer 1:</p>
                    <input type="text" name="option1" required/>
                    <p class="addQ">Answer 2:</p>
                    <input type="text" name="option2" required/>
                    <p class="addQ">Answer 3:</p>
                    <input type="text" name="option3" required/>
                    <p class="addQ">Answer 4:</p>
                    <input type="text" name="option4" required/>
                    <p class="addQ">Correct Answer:
                            <select name="correctAnswer">
                                <option value="1">Answer 1</option>
                                <option value="2">Answer 2</option>
                                <option value="3">Answer 3</option>
                                <option value="4">Answer 4</option>
                            </select>
                    </p>
                    <input id="addbtn" type="submit" value="Add Question"/>
                </form>
            </div>
        </div>
    </body>
</html>