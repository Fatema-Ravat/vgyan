<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }

//show message from add / edit page
if(isset($_GET['delwiki'])){ 

    $stmt = $db->prepare('DELETE FROM Wiki WHERE wikiID = :wikiID') ;
    $stmt->execute(array(':wikiID' => $_GET['delwiki']));

     //Need to find how to delete wiki reference from articles 

   /* $stmt = $db->prepare('DELETE FROM article_category WHERE catID = :catID');
    $stmt->execute(array(':catID' => $_GET['delcat']));*/


    header('Location: wikis.php?action=deleted');
    exit;
} 

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Wiki</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
  <script language="JavaScript" type="text/javascript">
  function delcat(wid, wtitle)
  {
      if (confirm("Are you sure you want to delete '" + wtitle + "'"))
      {
          window.location.href = 'wikis.php?delwiki=' + wid;
      }
  }
  </script>
</head>
<body>

    <div id="wrapper">

    <?php include('menu.php');?>

    <?php 
    //show message from add / edit page
    if(isset($_GET['action'])){ 
        echo '<h3>Wiki '.$_GET['action'].'.</h3>'; 
    } 
    ?>

    <table>
    <tr>
        <th>Title</th>
        <th>Action</th>
    </tr>
    <?php
        try {

            $stmt = $db->query('SELECT wikiID, wikiTitle FROM Wiki ORDER BY wikiID DESC');
            while($row = $stmt->fetch()){
                
                echo '<tr>';
                echo '<td>'.$row['wikiTitle'].'</td>';
                ?>

                <td>
                    <a href="edit-wiki.php?id=<?php echo $row['wikiID'];?>">Edit</a> | 
                    <a href="javascript:delcat('<?php echo $row['wikiID'];?>','<?php echo $row['wikiTitle'];?>')">Delete</a>
                </td>
                
                <?php 
                echo '</tr>';

            }

        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    ?>
    </table>

    <p><a href='add-wiki.php'>Add Wiki</a></p>

</div>

</body>
</html>