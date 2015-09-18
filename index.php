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

    <div id="wrapper">
        <div id="header">
           <?php require('header.php'); ?> 
        
        </div>
     <!--   <p> Latest News from the field of science and technology</p> -->  
          <hr />
         <div id="sidebar">
        <?php require('sidebar.php'); ?>
        </div>

        <div id="center">
            
        </div>
        <div id="main">
        <?php
            try {

                $stmt= $db->query('SELECT articleID,articleTitle,articleDesc,articleDateTime FROM articles ORDER BY articleID DESC');
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

    </div>


</body>
</html>