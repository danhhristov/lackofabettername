<?php 
    require_once 'config.php';
    require_once 'updateScores.php';
    
    $loginErr = $user = $userid = "";
    $correct = $all = $uanswered = $i = $uscore = 0;
    $stats;
    
    if(isset($_SESSION["username"]))
    {
        $user = $_SESSION["username"];
        $sql = "SELECT * FROM `USER` WHERE `username` LIKE '$user'";
        $result = $conn->query($sql);
        if(!$result)
        {
            die("Query failed".$conn->connect_error);
        }
        else{
            $row=$result->fetch_assoc();
            $user = $row['First Name'];
            $userid = $row['id'];
            $uscore = $row["score"];
        }
        
        foreach($categories as $category)
        {
            $sql = "SELECT * FROM `QUESTION` WHERE `category` LIKE '$category' ORDER BY `answer_id` ASC";
            $result = $conn->query($sql);
            if(!$result){die("Query failed".$conn->connect_error);}
            else
            {
                $all = $result->num_rows;
                $uanswered = 0;
                $correct = 0;
                while($row=$result->fetch_assoc())
                {
                    $qid = $row['id'];
                    $answered = "SELECT * FROM `UANSWERS` WHERE `user_id` = $userid AND `q_id` = $qid";
                    $answeredResult = $conn->query($answered);

                    if($answeredResult->num_rows == 1) {
                        $uanswered++;
                    }

                    if($row1=$answeredResult->fetch_assoc()) 
                    {
                        if($row1['isCorrect'] == 1)
                            $correct++;
                    }
                }

                $stats[$i] = [$category, $uanswered, $all, $correct];
            }
            ++$i;
            }
        
    }
    else{
        header("Location:login.php");
    }
    
    
?>
<html>
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <?php
        require_once 'frame.php';
        ?>
        <div id="profile">
            <h1>Hello, <?php echo $user;?>!</h1>
            <div id="profileStats">
                
                <h3>Personal statistics: </h3>
                <div id="score">Score: <?php echo $uscore;?></div>
                <?php
                echo "<table>";
                echo "<tr><td class=\"theading\">Category</td>";
                foreach ($stats as $row)
                {
                    echo "<td class=\"cheading\">$row[0]</td>";
                }
                echo "</tr>";
                echo "<tr><td class=\"theading\">Questions Answered</td>";
                foreach ($stats as $row)
                {
                    echo "<td class=\"centerAl\">$row[1]/$row[2]</td>";
                }
                echo "</tr>";
                echo "<tr><td class=\"theading\">Correct Answers</td>";
                foreach ($stats as $row)
                {
                    echo "<td class=\"centerAl\">$row[3]/$row[1]</td>";
                }
                echo "</tr>";
                echo "</table><br>";
                ?>
            </div>
        
        </div>
    </body>
</html>
