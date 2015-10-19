<?php 
require('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Wiki</title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

       <div id="header">
           <?php require('header.php'); ?> 
        
        </div>
      

        <div id="wrapper">

        <?php
            try {

             //collect search text data
			$wikiTitle = $_GET['wTitle'];
	         
            if(!empty($wikiTitle))
            {
            
                //search in wiki
                $stmt1 = $db->prepare("SELECT wikiTitle, wikiDesc FROM Wiki WHERE wikiTitle = '".$wikiTitle."' ORDER BY wikiID DESC");
                $stmt1->execute(array());   

              if($row1 = $stmt1->fetch()){
                echo '<div>';
                     echo '<h1>'.$row1['wikiTitle'].'</h1>';
                    echo '</p>';
                    echo '<p>'.$row1['wikiDesc'].'</p>';                                
                echo '</div>';
                }
            else
                echo "Sorry!! Wiki not available";
            }
			}catch(PDOException $e){
				echo $e->getMessage();
			}
        ?>
    </div>


</body>
</html>