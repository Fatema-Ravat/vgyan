<?php 
require('includes/config.php'); 

$stmt = $db->prepare('SELECT articleID, articleTitle, articleCont, articleDateTime, articleSource FROM articles WHERE articleID = :articleID');
$stmt->execute(array(':articleID' => $_GET['id']));
$row = $stmt->fetch();

//if post does not exists redirect user.
if($row['articleID'] == ''){
    header('Location: ./');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Article - <?php echo $row['articleTitle'];?></title>
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
            echo '<div>';
                echo '<h1>'.$row['articleTitle'].'</h1>';
                echo '<p>Posted on '.date('jS M Y', strtotime($row['articleDateTime'])).'</p>';
                echo '<p>'.$row['articleCont'].'</p>';    
                echo '<p> Source: '.$row['articleSource'].'</p>';         
            echo '</div>';
        ?>
        </div>
          <div id="sidebar">
            <?php require('sidebar.php'); ?>
        </div>
       
    </div>


</body>
</html>