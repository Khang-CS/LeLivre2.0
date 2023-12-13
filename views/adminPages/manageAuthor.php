<?php

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:index.php?controller=log&action=login');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="../assets/css/admin_style.css">

</head>

<body>

    <?php include 'admin_header.php'; ?>

    <!-- product CRUD section starts  -->

    <section class="add-products">

        <h1 class="title">Authors</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <h3>add author</h3>
            <input type="text" name="Author_name" class="box" placeholder="enter author name" required>
            <input type="submit" value="add author" name="add_author" class="btn">
        </form>

    </section>

    <!-- product CRUD section ends -->

    <!-- show products  -->

    <section class="show-products">

        <div class="box-container">

            <?php
            if (!empty($authorList)) {
                foreach ($authorList as $author) {
            ?>
                    <form action="" method="post" enctype="multipart/form-data" class="box">
                        <div class="name">
                            <?php echo $author->Author_name; ?>
                        </div>
                        <input type="text" value="<?php echo $author->Author_ID; ?>" name="Author_ID" hidden>
                        <a href="index.php?controller=adminPages&action=manageAuthor&update=<?php echo $author->Author_ID; ?>&updateAuthor=<?php echo $author->Author_name; ?>" class="option-btn">update</a>
                        <input type="submit" value="delete" name="delete_author" class="delete-btn" onclick="return confirm('delete this author?');">
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>

    </section>

    <section class="edit-product-form">

        <?php
        if (isset($_GET['update'])) {

        ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="Author_ID" value="<?php echo $_GET['update']; ?>">
                <input type="text" name="Author_name" value="<?php echo $_GET['updateAuthor']; ?>" class="box" required placeholder="enter author name">
                <input type="submit" value="update" name="update_author" class="btn">
                <a href="index.php?controller=adminPages&action=manageAuthor" value="cancel" class="option-btn">Cancel</a>
            </form>
        <?php

        } else {
            echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
        }
        ?>

    </section>







    <!-- custom admin js file link  -->
    <script src="../assets/js/admin_script.js"></script>

</body>

</html>