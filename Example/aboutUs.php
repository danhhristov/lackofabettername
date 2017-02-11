<?php
session_start();
require_once 'config.php';
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
        
        <div id="aboutUs-container">
            <h2>CS 312 Group Q</h2>
            <div id="aboutUs-container-inner">
                <table id="about-table">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                    </tr>
                    <tr>
                        <td>Daniel Hristov</td>
                        <td class="middleAl">yxb14139</td>
                        <td>daniel.hristov.2014@uni.strath.ac.uk</td>
                    </tr>
                    <tr>
                        <td>Svetlozar Deskov</td>
                        <td class="middleAl">ekb13155</td>
                        <td>svetlozar.deskov.2013@uni.strath.ac.uk</td>
                    </tr>
                    <tr>
                        <td>Anton Obretenov</td>
                        <td class="middleAl">kkb14183</td>
                        <td>anton.obretenov.2014@uni.strath.ac.uk</td>
                    </tr>
                    <tr>
                        <td>Dimitar Genov</td>
                        <td class="middleAl">qnb14161</td>
                        <td>dimitar.genov.2014@uni.strath.ac.uk</td>
                    </tr>
                    <tr>
                        <td>Veselin Genchev</td>
                        <td class="middleAl">trb14153</td>
                        <td>veselin.genchev.2014@uni.strath.ac.uk</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>