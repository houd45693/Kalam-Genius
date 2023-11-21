<?php
include("db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $news_title = $_POST['news_title'];
    $news_image = $_FILES['news_image']['name'];
    $news_image_tmp_name = $_FILES['news_image']['tmp_name'];
    $news_image_folder = 'assets/uploaded_img/' . $news_image;

    $message = [];

    if (empty($news_title) || empty($news_image)) {
        $message[] = 'Please fill out all fields.';
    } else {
        $insert = "INSERT INTO news (news_title, news_image) VALUES(?, ?)";
        $stmt = mysqli_prepare($con, $insert);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $news_title, $news_image);
            $upload = mysqli_stmt_execute($stmt);

            if ($upload) {
                // Check if the file was successfully uploaded before moving it
                if (is_uploaded_file($news_image_tmp_name)) {
                    move_uploaded_file($news_image_tmp_name, $news_image_folder);
                    $message[] = 'New news added successfully.';
                } else {
                    $message[] = 'Error: File upload failed.';
                }
            } else {
                $message[] = 'Could not add the news.';
            }

            mysqli_stmt_close($stmt);
        } else {
            $message[] = 'Error: Failed to prepare the statement.';
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM news WHERE id = $id");
    header('location: http://localhost/kalamgenius/staffIndex.php');
    exit;
}
?>


<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" type="image/png" href="./assets/images/LOGO1.png" />
<title>Kalam Genius Staff</title>

<!-- Icons CSS -->
<link rel="stylesheet" href="css/feather.css">
<!-- App CSS -->
<link rel="stylesheet" href="./assets/css/app-light.css" id="lightTheme">

<!-- font awesome cdn link  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- custom css file link  -->
<link rel="stylesheet" href="./assets/css/stylo.css">


</head>
<body>
<nav class="navbar fixed-top navbar-light bg-info">
    <div class="container-fluid d-flex justify-content-between">
        <span class="navbar-brand">Kalam Genius Staff Dashboard</span>
        <div class="ml-auto">
            <a href="index.php" class="btn btn-danger">
                <i class="fe fe-lock fe-12 mr-2"></i><span class="big">Log Out</span>
            </a>
        </div>
    </div>
</nav>

<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '<span class="message">' . $message . '</span>';
    }
}

?>

<div class="containers">

    <div class="admin-product-form-container">


        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <h3>add new news</h3>
            <input type="text" placeholder="Enter news title" name="news_title" class="box">
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="news_image" class="box">
            <input type="submit" class="btn" name="add_news" value="add news">
        </form>

    </div>

    <?php

    $select = mysqli_query($con, "SELECT * FROM news");

    ?>
    <div class="product-display">
        <table class="product-display-table">
            <thead>
            <tr>
                <th>news image</th>
                <th>news title</th>
                <th>action</th>
            </tr>
            </thead>
            <?php while ($row = mysqli_fetch_assoc($select)) { ?>
                <tr>
                <td><img src="assets/uploaded_img/<?php echo $row['news_image']; ?>" height="100" alt=""></td>
                    <td><?php echo $row['news_title']; ?></td>
                    <td>
                        <a href="admin_update.php?edit=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-edit"></i> edit </a>
                        <a href="staffIndex.php?delete=<?php echo $row['id']; ?>" class="btn" 
                        onclick="return confirm('Are you sure you want to delete this news entry?')"> <i class="fas fa-trash"></i> delete </a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</div>



</main> <!-- main -->
</div> <!-- .wrapper -->

<script src="./assets/js/bootstrap.min.js"></script>
<script src="./assets/js/simplebar.min.js"></script>
<script src="./assets/js/tinycolor-min.js"></script>
<script src="./assets/js/config.js"></script>
<script src="js/apps.js"></script>
</body>

</html>