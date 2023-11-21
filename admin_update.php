<?php

@include 'db.php';

$id = $_GET['edit'];

if(isset($_POST['update_news'])){

   $news_title = $_POST['news_title'];
   $news_image = $_FILES['news_image']['name'];
   $news_image_tmp_name = $_FILES['news_image']['tmp_name'];
   $news_image_folder = 'assets/uploaded_img/' . $news_image;

   if(empty($news_title) || empty($news_image)){
      $message[] = 'Please fill out all!';
   } else {

      $update_data = "UPDATE news SET news_title=?, news_image=? WHERE id=?";
      $stmt = mysqli_prepare($con, $update_data);
      mysqli_stmt_bind_param($stmt, "ssi", $news_title, $news_image, $id);
      
      if ($stmt) {
         mysqli_stmt_bind_param($stmt, "ssi", $news_title, $news_image, $id);
         $upload = mysqli_stmt_execute($stmt);

         if($upload){
               move_uploaded_file($news_image_tmp_name, $news_image_folder);
               header('location: http://localhost/kalamgenius/staffIndex.php');
               exit;
         } else {
               $message[] = 'Failed to update. Please try again!';
         }

         mysqli_stmt_close($stmt);
      } else {
         die("Preparation failed: " . mysqli_error($con));
      }
   }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="./assets/css/stylo.css">
</head>
<body>

<?php
   if(isset($message)){
      foreach($message as $message){
         echo '<span class="message">'.$message.'</span>';
      }
   }
?>

<div class="container">


<div class="admin-product-form-container centered">

   <?php
      
      $select = mysqli_query($con, "SELECT * FROM news WHERE id = '$id'");
      while($row = mysqli_fetch_assoc($select)){

   ?>
   
   <form action="" method="post" enctype="multipart/form-data">
      <h3 class="title">update the news</h3>
      <input type="text" class="box" name="news_title" value="<?php echo $row['news_title']; ?>" placeholder="enter the news title">
      <input type="file" class="box" name="news_image"  accept="image/png, image/jpeg, image/jpg">
      <input type="submit" value="update product" name="update_news" class="btn">
      <a href="staffIndex.php" class="btn">go back!</a>
   </form>
   


   <?php }; ?>

   

</div>

</div>

</body>
</html>