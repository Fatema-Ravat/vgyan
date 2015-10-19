<?php 
require('includes/config.php'); 

$stmt = $db->prepare('SELECT articleID, articleTitle, articleCont, articleIssueDate, articleSource,articleImage FROM articles WHERE articleID = :articleID');
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

 

        <div id="header">
           <?php require('header.php'); ?> 
        
        </div>
       

        <div id="wrapper">
        <?php    
            echo '<div>';
                echo '<h2 style="padding-top: 10%;">'.$row['articleTitle'].'</h2>';
                echo '<IMG style="float:center" SRC="./images/'.$row['articleImage'].'" ALT="PIC" WIDTH=960 HEIGHT=250>';          
                echo '<p>Issue date: '.date('jS M Y', strtotime($row['articleIssueDate'])).'</p>';
                echo '<p>'.$row['articleCont'].'</p>';    
                echo '<p> Source: <a href="'.$row['articleSource'].'">Read from source</a></p>';         
            echo '</div>';
        ?>
    <!--    </div>
          <div id="sidebar">
            <?php require('sidebar.php'); ?>
        </div>
  -->     

</body>
</html>