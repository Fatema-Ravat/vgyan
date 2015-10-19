<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Edit Wiki</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
</head>
<body>

<div id="wrapper">

    <?php include('menu.php');?>
    <p><a href="wikis.php">Wikis</a></p>

    <h2>Edit Wiki</h2>


    <?php

    //if form has been submitted process it
    if(isset($_POST['submit'])){

        $_POST = array_map( 'stripslashes', $_POST );

        //collect form data
        extract($_POST);

        //very basic validation
        if($wikiID ==''){
            $error[] = 'This post is missing a valid id!.';
        }

        // should not be able to change wikiTitle as it is the link in article
        // put code for that. 
        if($wikiTitle ==''){
            $error[] = 'Please enter the title';
        }

        if($wikiDesc ==''){
            $error[] = 'Please enter the Description';
        }

        if(!isset($error)){

            try {

               // $catSlug = slug($catTitle);

                //insert into database
                $stmt = $db->prepare('UPDATE Wiki SET wikiDesc = :wikiDesc WHERE wikiID = :wikiID') ;
                $stmt->execute(array(
                    ':wikiDesc' => $wikiDesc,
                    ':wikiID' => $wikiID
                ));

                //redirect to index page
                header('Location: wikis.php?action=updated');
                exit;

            } catch(PDOException $e) {
                echo $e->getMessage();
            }

        }

    }

    ?>


    <?php
    //check for any errors
    if(isset($error)){
        foreach($error as $error){
            echo $error.'<br />';
        }
    }

        try {

            $stmt = $db->prepare('SELECT wikiID, wikiTitle,wikiDesc FROM Wiki WHERE wikiID = :wikiID') ;
            $stmt->execute(array(':wikiID' => $_GET['id']));
            $row = $stmt->fetch(); 

        } catch(PDOException $e) {
            echo $e->getMessage();
        }

    ?>

    <form action='' method='post'>
        <input type='hidden' name='wikiID' value='<?php echo $row['wikiID'];?>'>

        <p><label>Title</label><br />
        <input type='text' name='wikiTitle' value='<?php echo $row['wikiTitle'];?>' readonly></p>

        <p><label>Description</label><br />
        <input type='textarea' name='wikiDesc' value='<?php echo $row['wikiDesc'];?>'></p>

        <p><input type='submit' name='submit' value='Update'></p>

    </form>

</div>

</body>
</html>    