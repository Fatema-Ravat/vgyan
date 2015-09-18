<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Add New Article</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
  <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
  <script>
          tinymce.init({
              selector: "textarea",
              plugins: [
                  "advlist autolink lists link image charmap print preview anchor",
                  "searchreplace visualblocks code fullscreen",
                  "insertdatetime media table contextmenu paste"
              ],
              toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
              });
  </script>
</head>
<body>

<div id="wrapper">

    <?php include('menu.php');?>
    <p><a href="./">viGyanSanchay Admin Index</a></p>

    <h2>Add New Article</h2>

    <?php

    //if form has been submitted process it
    if(isset($_POST['submit'])){

       //$_POST = array_map( 'stripslashes', $_POST );

        //collect form data
        extract($_POST);

        //very basic validation
        if($articleTitle ==''){
            $error[] = 'Please enter the title.';
        }

        if($articleDesc ==''){
            $error[] = 'Please enter the description.';
        }

        if($articleCont ==''){
            $error[] = 'Please enter the content.';
        }

        if($articleSource ==''){
            $error[] = 'Please enter the article source.';
        }

        if(!isset($error)){

            try {

                //insert into database
                $stmt = $db->prepare('INSERT INTO articles (articleTitle,articleDesc,articleCont,articleDateTime,articleSource) 
                  VALUES (:articleTitle, :articleDesc, :articleCont, :articleDateTime,:articleSource)') ;
                $stmt->execute(array(
                    ':articleTitle' => $articleTitle,
                    ':articleDesc' => $articleDesc,
                    ':articleCont' => $articleCont,
                    ':articleDateTime' => date('Y-m-d H:i:s'),
                    ':articleSource' => $articleSource
                ));

                $articleID = $db->lastInsertId();

                //add categories
                if(!empty($_POST['catIDArr'])){
                foreach($_POST['catIDArr'] as $catIDSel){
                  //echo $articleID . " " .$catIDSel."</br>";
                  $stmt2 = $db->prepare('INSERT INTO article_category (articleID,catID)VALUES(:articleID,:catID)');
                   $stmt2->execute(array(
                  ':articleID' => $articleID,
                  ':catID' => $catIDSel
                ));
                }
              }

                //redirect to index page
                header('Location: index.php?action=added');
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

        <p><label>Title</label><br />
        <input type='text' name='articleTitle' value='<?php if(isset($error)){ echo $_POST['articleTitle'];}?>'></p>

        <p><label>Description</label><br />
        <textarea name='articleDesc' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['articleDesc'];}?></textarea></p>

        <p><label>Content</label><br />
        <textarea name='articleCont' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['articleCont'];}?></textarea></p>


        <p><label>Article Source</label></br>
        <input type='url' name='articleSource'></p>

        <fieldset>
          <legend>Categories</legend>

          <?php    

          
          $stmt2 = $db->query('SELECT catID, catTitle FROM Category ORDER BY catTitle');
          while($row2 = $stmt2->fetch()){

         /*   if(isset($_POST['catID'])){

              if(in_array($row2['catID'], $_POST['catID'])){
                $checked="checked='checked'";
              }else{
                $checked = null;
              }
            }*/
             $checked = null;

            echo "<input type='checkbox' name='catIDArr[]' value='".$row2['catID']."' $checked> ".$row2['catTitle']."<br />";
            //echo "<option value=".$row2['catID'].">".$row2['catTitle']."</option>";
        
         }
       
        ?>

        </fieldset>
        <p><input type='submit' name='submit' value='Submit'></p>

    </form>

</div>