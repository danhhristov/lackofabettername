<?php

require_once 'config.php';
require_once 'updateScores.php';


?>
<html>
    <head>
        <title>Rankings</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <?php
        require_once 'frame.php';
        ?>
        <div id="rankings-container">
            <h3>Current User Ranking</h3>
            <div id="rankings-container-inner">
                <table id="ranking-table">
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>Score</th>
                    </tr>
                    <?php
                    $getUsersByScore = "SELECT * FROM `USER` ORDER BY `score` DESC";
                    $usersOrdered = $conn->query($getUsersByScore);
                    $counter = 1;
                    while($user = $usersOrdered->fetch_assoc()){
                        echo "<tr><td class=\"middleAl\">$counter</td>"."<td>".$user["username"]."</td><td class=\"middleAl\">".$user["score"]."</td></tr>";
                        $counter++;
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>
