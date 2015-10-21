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

    

       <div id="header">
           <?php require('header.php'); ?> 
        
        </div>
      

        <div id="wrapper">

            <ul>
            <form action="searchbydate.php" method="GET">
            <p>તારીખ દ્વારા શોધો: </br>
                <input type="date" name="searchText" value="Search by date" /> 
                <input type="submit" name="submit" value="શોધ" /></p>
            </ul>
        <hr/>
        <h3 > દફ્તર-સંગ્રહ </h3>

        <ul>
        <?php

            $stmtArch = $db->query("SELECT Year(articleIssueDate) as Year FROM Articles GROUP BY Year(articleIssueDate) ORDER BY articleIssueDate DESC");
            while($yr = $stmtArch->fetch()){
                echo "<li> ".$yr['Year']."</li>";
                echo "<ul>";
                $stmt3 = $db->query("SELECT Month(articleIssueDate) as Month FROM Articles WHere Year(articleIssueDate) =".$yr['Year']." GROUP BY Month(articleIssueDate) ORDER BY articleIssueDate DESC");
                while($row = $stmt3->fetch()){
                $monthName = date("F", mktime(0, 0, 0, $row['Month'], 10));
                 //echo "<li><a href=arch-mon-yr.php?month=".$row['Month']."&year=".$yr['Year'].">".$monthName."</a></li>";
                   echo "<li>".$monthName."</li>";
                    echo "<ul>";
                    $stmt4 = $db->query("SELECT articleIssueDate as PublishDate FROM Articles WHere Month(articleIssueDate) =".$row['Month']." GROUP BY articleIssueDate ORDER BY articleIssueDate DESC");
                    while($row1 = $stmt4->fetch()){
                            echo "<li><a href=arch-mon-yr.php?publishDate=".$row1['PublishDate'].">".$row1['PublishDate']."</a></li>";
                    }
                    echo "</ul>";
                }
                echo "</ul>";
            }
        ?>
        </ul>
    
    <!-- <div id="sidebar">
        <?php require('sidebar.php'); ?>
        </div> -->

    </div>


</body>
</html>
