<?php 
require('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Articles</title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

    
    <div id="header">
        <?php require('header.php'); ?> 
           
    </div>
   
     <!--    <div id="sidebar">
        <?php require('sidebar.php'); ?>
        </div>
            -->
       
    <div id="wrapper">
        <p style='background-color: #87CEEB; height: 75px; font-size: x-large;'> વિજ્ઞાન તથા ટેકનોલોજી ક્ષેત્ર ના તાજા સમાચાર.</p>
        <?php
            try {

                $stmt= $db->query('SELECT articleID,articleTitle,articleDesc,articleIssueDate,articleImage FROM articles WHERE articleIssueDate = (select max(articleIssueDate) from articles where articleIssueDate <= CURRENT_DATE) ORDER BY articleID DESC');
			while($row = $stmt->fetch()){
				echo '<div>';
					 echo '<h2><a href="viewarticle.php?id='.$row['articleID'].'">'.$row['articleTitle'].'</a></h2>';
                     echo '<p>'.date('jS M Y', strtotime($row['articleIssueDate'])).' : ';

                        $stmt2 = $db->prepare('SELECT Category.catID,catTitle, catSlug    FROM Category, article_category WHERE Category.catID = article_category.catID AND article_category.articleID = :articleID');
                        $stmt2->execute(array(':articleID' => $row['articleID']));

                        $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                        $links = array();
                        foreach ($catRow as $cat){
                            $links[] = "<a href=article-category.php?id=".$cat['catID'].">".$cat['catTitle']."</a>";
                        }
                        echo implode(", ", $links);

                    echo '</p>';
                  
                    echo '<IMG style="float:right;" SRC="./images/'.$row['articleImage'].'" ALT="PIC" WIDTH=300 HEIGHT=150>';          
                	echo '<p>'.$row['articleDesc'].'</p>';  
                
                    echo '<p><a href="viewarticle.php?id='.$row['articleID'].'">આગળ વાંચો</a></p>';                
            	echo '</div>';
				}
			}catch(PDOException $e){
				echo $e->getMessage();
			}
        ?>
    </div>

</body>
</html>