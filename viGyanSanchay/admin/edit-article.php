<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Edit Article</title>
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

    <h2>Edit Article</h2>


    <?php

    //if form has been submitted process it
    if(isset($_POST['submit'])){

        //$_POST = array_map( 'stripslashes', $_POST );

        //collect form data
        extract($_POST);

        //very basic validation
        if($articleID ==''){
            $error[] = 'This post is missing a valid id!.';
        }

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
                $stmt = $db->prepare('UPDATE articles SET articleTitle = :articleTitle, articleDesc = :articleDesc, articleCont = :articleCont, articleSource = :articleSource 
                    WHERE articleID = :articleID') ;
                $stmt->execute(array(
                    ':articleTitle' => $articleTitle,
                    ':articleDesc' => $articleDesc,
                    ':articleCont' => $articleCont,
                    ':articleSource' => $articleSource,
                    ':articleID' => $articleID
                ));

                //delete all items with the current articleID
            $stmt = $db->prepare('DELETE FROM article_category WHERE articleID = :articleID');
            $stmt->execute(array(':articleID' => $articleID));

            if(is_array($catID)){
            foreach($_POST['catID'] as $catID){
                $stmtCat = $db->prepare('INSERT INTO article_category (articleID,catID)VALUES(:articleID,:catID)');
                $stmtCat->execute(array(
                    ':articleID' => $articleID,
                    ':catID' => $catID
                ));
    }
}

                //redirect to index page
                header('Location: index.php?action=updated');
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

            $stmt = $db->prepare('SELECT articleID, articleTitle, articleDesc, articleCont, articleSource FROM articles WHERE articleID = :articleID') ;
            $stmt->execute(array(':articleID' => $_GET['id']));
            $row = $stmt->fetch(); 

        } catch(PDOException $e) {
            echo $e->getMessage();
        }

    ?>

    <form action='' method='post'>
        <input type='hidden' name='articleID' value='<?php echo $row['articleID'];?>'>

        <p><label>Title</label><br />
        <input type='text' name='articleTitle' value='<?php echo $row['articleTitle'];?>'></p>

        <p><label>Description</label><br />
        <textarea name='articleDesc' cols='60' rows='10'><?php echo $row['articleDesc'];?></textarea></p>

        <p><label>Content</label><br />
        <textarea name='articleCont' cols='60' rows='10'><?php echo $row['articleCont'];?></textarea></p>

        <input type='url' name='articleSource' value='<?php echo $row['articleSource'];?>'>

        <fieldset>
            <legend>Categories</legend>

            <?php

                $stmt2 = $db->query('SELECT catID, catTitle FROM Category ORDER BY catTitle');
                while($row2 = $stmt2->fetch()){

                    $stmt3 = $db->prepare('SELECT catID FROM article_category WHERE catID = :catID AND articleID = :articleID') ;
                   $stmt3->execute(array(':catID' => $row2['catID'], ':articleID' => $row['articleID']));
                    $row3 = $stmt3->fetch(); 

                    if($row3['catID'] == $row2['catID']){
                        $checked = 'checked=checked';
                    } else {
                        $checked = null;
                    }

                echo "<input type='checkbox' name='catID[]' value='".$row2['catID']."' $checked> ".$row2['catTitle']."<br />";
            }

        ?>

        </fieldset>

        <p><input type='submit' name='submit' value='Update'></p>

    </form>

</div>

</body>
</html>  