<?php
require_once 'config.php';


$category = safePost($conn,"search_option");

$listings = "select * from `Posts` where `categories` LIKE '$category'";

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Listings</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <?php
        //require_once 'frame.php';
        ?>
        <div class="main-content">
            <?php
            $listings_results = $conn->query($listings);
            if($listings_results->num_rows > 0){
                while ($row = $listings_results->fetch_assoc()) {
                        echo "Post name:".$row["name"];
                }
            }
            ?>
        </div>
    </body>
</html>