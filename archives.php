<?php 
require('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Archives</title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

    <div id="wrapper">

       <div id="header">
           <?php require('header.php'); ?> 
        
        </div>
        <hr />
      

        <div id="main">

            <ul>
            <form action="search.php" method="GET">
            <p><input type="text" name="searchText" value="" /> 
                <input type="submit" name="submit" value="Search" /></p>
            </ul>
        <hr/>
        <h3 > Archives </h3>

        <ul>
        <?php

            $stmtArch = $db->query("SELECT Year(articleDateTime) as Year FROM Articles GROUP BY Year(articleDateTime) ORDER BY articleDateTime DESC");
            while($yr = $stmtArch->fetch()){
                echo "<li> Year : ".$yr['Year']."</li>";
                echo "<ul>";
                $stmt3 = $db->query("SELECT Month(articleDateTime) as Month FROM Articles WHere Year(articleDateTime) =".$yr['Year']." GROUP BY Month(articleDateTime) ORDER BY articleDateTime DESC");
                while($row = $stmt3->fetch()){
                $monthName = date("F", mktime(0, 0, 0, $row['Month'], 10));
                echo "<li><a href=arch-mon-yr.php?month=".$row['Month']."&year=".$yr['Year'].">".$monthName."</a></li>";
                }
                echo "</ul>";
            }
        ?>
        </ul>
    </div>
     <div id="sidebar">
        <?php require('sidebar.php'); ?>
    </div>

    </div>


</body>
</html>
