<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Add Wiki</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
</head>
<body>

<div id="wrapper">

    <?php include('menu.php');?>
    <p><a href="wikis.php">Wikis</a></p> 

    <h2>Add Wiki</h2>

    <?php

    //if form has been submitted process it
    if(isset($_POST['submit'])){

        $_POST = array_map( 'stripslashes', $_POST );

        //collect form data
        extract($_POST);

        //very basic validation
        if($wikiTitle ==''){
            $error[] = 'Please enter the Wiki Title';
        }
        if($wikiDesc ==''){
            $error[] = 'Please enter the Wiki Description';
        }

        if(!isset($error)){

            try {

                
                //insert into database
                $stmt = $db->prepare('INSERT INTO Wiki (wikiTitle,wikiDesc) VALUES (:wikiTitle,:wikiDesc)') ;
                $stmt->execute(array(
                    ':wikiTitle' => $wikiTitle,
                    ':wikiDesc' => $wikiDesc
                ));

                //redirect to index page
                header('Location: wikis.php?action=added');
                exit;

            } catch(PDOException $e) {
                echo $e->getMessage();
            }

        }

    }

    //check for any errors
    if(isset($error)){
        foreach($error as $error){
            echo '<p class="error">'.$error.'</p>';
        }
    }
    ?>

    <form action='' method='post'>

        <p><label>Wiki Title</label><br />
        <input type='text' name='wikiTitle' value='<?php if(isset($error)){ echo $_POST['wikiTitle'];}?>'></p>
        
        <p><label>Wiki Description</label><br />
        <input type='textarea' name='wikiDesc' value='<?php if(isset($error)){ echo $_POST['wikiDesc'];}?>'></p>
        
        <p><input type='submit' name='submit' value='Submit'></p>

    </form>

</div>