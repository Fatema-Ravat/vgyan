<?php require('includes/config.php'); 


$stmt = $db->prepare('SELECT catID,catTitle FROM Category WHERE catID = :catID');
$stmt->execute(array(':catID' => $_GET['id']));
$row = $stmt->fetch();

//if post does not exists redirect user.
if($row['catID'] == ''){
    header('Location: ./');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Category - <?php echo $row['catTitle'];?></title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

    

     <div id="header">
           <?php require('header.php'); ?> 
        
        </div>
      

        <div id="wrapper">  
        <h2>Category: <?php echo $row['catTitle'];?></h2>
        

        <?php    
        try {

            $stmt = $db->prepare('
                SELECT 
                    Articles.articleID, Articles.articleTitle, Articles.articleDesc, Articles.articleDateTime 
                FROM 
                    Articles,
                    article_category
                WHERE
                     Articles.articleID = article_category.articleID
                     AND article_category.catID = :catID
                ORDER BY 
                    articleID DESC
                ');
            $stmt->execute(array(':catID' => $row['catID']));
            while($row = $stmt->fetch()){
                
                echo '<div>';
                    echo '<h3><a href="'.$row['articleID'].'">'.$row['articleTitle'].'</a></h3>';
                    echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['articleDateTime'])).' in ';

                        $stmt2 = $db->prepare('SELECT Category.catID, catTitle, catSlug    FROM Category, article_category WHERE Category.catID = article_category.catID AND article_category.articleID = :articleID');
                        $stmt2->execute(array(':articleID' => $row['articleID']));

                        $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                        $links = array();
                        foreach ($catRow as $cat)
                        {
                            $links[] = "<a href=article-category.php?id=".$cat['catID'].">".$cat['catTitle']."</a>";
                        }
                        echo implode(", ", $links);

                    echo '</p>';
                    echo '<p>'.$row['articleDesc'].'</p>';                
                    echo '<p><a href="'.$row['articleID'].'">Read More</a></p>';                
                echo '</div>';

            }

        } catch(PDOException $e) {
            echo $e->getMessage();
        }

        ?>
    </div>


</body>
</html>