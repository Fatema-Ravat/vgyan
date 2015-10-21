<?php 
require('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Search</title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

       <div id="header">
           <?php require('header.php'); ?> 
        
        </div>
      

        <div id="wrapper">

          <!--  <ul>
            <form action="search.php" method="GET">
            <p><input type="text" name="searchText" value=<?php echo $_GET['searchText'] ?> /> 
                <input type="submit" name="submit" value="Search" /></p>
            </ul>
            <hr/> 
        -->
        <?php
            try {

             //collect search text data
			$searchTxt = $_GET['searchText'];
	         
            if(!empty($searchTxt))
            {

            //search in articles
			 $stmt = $db->prepare("SELECT articleID,articleTitle, articleDesc, articleIssueDate FROM Articles WHERE articleDesc like  '%".$searchTxt."%' ORDER BY articleIssueDate DESC");
			 $stmt->execute(array());   

             while($row = $stmt->fetch()){
				echo '<div>';
					 echo '<h1><a href="viewarticle.php?id='.$row['articleID'].'">'.$row['articleTitle'].'</a></h1>';
                     echo '<p>Posted on '.date('jS M Y', strtotime($row['articleIssueDate'])).' : ';

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

                //search in wiki
                $stmt1 = $db->prepare("SELECT wikiTitle, wikiDesc FROM Wiki WHERE wikiDesc like  '%".$searchTxt."%' ORDER BY wikiID DESC");
                $stmt1->execute(array());   

             while($row1 = $stmt1->fetch()){
                echo '<div>';
                     echo '<h1>'.$row1['wikiTitle'].'</h1>';
                    echo '</p>';
                    echo '<p>'.$row1['wikiDesc'].'</p>';                                
                echo '</div>';
                }
            }
			}catch(PDOException $e){
				echo $e->getMessage();
			}
        ?>
    </div>


</body>
</html>