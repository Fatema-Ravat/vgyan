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
        <?php
            try {

             //collect month and year data
			$month = $_GET['month'];
			$year = $_GET['year'];

			//set from and to dates
			$from = date('Y-m-01 00:00:00', strtotime("$year-$month"));
			$to = date('Y-m-31 23:59:59', strtotime("$year-$month"));

			$stmt = $db->prepare('SELECT articleID,articleTitle, articleDesc, articleDateTime FROM Articles WHERE articleDateTime >= :from 
                AND articleDateTime <= :to ORDER BY articleID DESC');
			$stmt->execute(array(
    			':from' => $from,
    			':to' => $to
			));   
             while($row = $stmt->fetch()){
				echo '<div>';
					 echo '<h1><a href="viewarticle.php?id='.$row['articleID'].'">'.$row['articleTitle'].'</a></h1>';
                     echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['articleDateTime'])).' : ';

                        $stmt2 = $db->prepare('SELECT Category.catID,catTitle, catSlug    FROM Category, article_category WHERE Category.catID = article_category.catID AND article_category.articleID = :articleID');
                        $stmt2->execute(array(':articleID' => $row['articleID']));

                        $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                        $links = array();
                        foreach ($catRow as $cat){
                            $links[] = "<a href=article-category.php?id=".$cat['catID'].">".$cat['catTitle']."</a>";
                        }
                        echo implode(", ", $links);

                    echo '</p>';
                	echo '<p>'.$row['articleDesc'].'</p>';                
                	echo '<p><a href="viewarticle.php?id='.$row['articleID'].'">Read More</a></p>';                
            	echo '</div>';
				}
			}catch(PDOException $e){
				echo $e->getMessage();
			}
        ?>
    </div>

    <div id="sidebar">
        <?php require('sidebar.php'); ?>
    </div>

    </div>


</body>
</html>
