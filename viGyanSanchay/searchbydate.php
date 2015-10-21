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

            <ul>
            <form action="searchbydate.php" method="GET">
            <p><input type="date" name="searchText" value=<?php echo $_GET['searchText'] ?> /> 
                <input type="submit" name="submit" value="Search" /></p>
            </ul>
            <hr/>
        <?php
            try {

             //collect search text data
			$searchTxt = $_GET['searchText'];
	         
            // echo $searchTxt;
            //search in articles
			$stmt = $db->prepare("SELECT articleID,articleTitle, articleDesc, articleIssueDate FROM Articles WHERE articleIssueDate =( SELECT articleIssueDate from Articles where articleIssueDate <= str_to_date('".$searchTxt."','%Y-%m-%d') ORDER BY articleIssueDate DESC LIMIT 1)");
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

			}catch(PDOException $e){
				echo $e->getMessage();
			}
        ?>
    </div>


</body>
</html>