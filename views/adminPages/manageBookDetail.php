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
    <title><?php echo $book->Book_name; ?></title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="../assets/css/admin_style.css">

</head>

<body>

    <?php include 'admin_header.php'; ?>

    <!-- product CRUD section starts  -->

    <section class="add-products">

        <h1 class="title">View and Update</h1>

        <form onsubmit="return confirmSubmission()" action="" method="post" enctype="multipart/form-data">
            <h3><?php echo $book->Book_name; ?></h3>
            <input type="hidden" name="Book_ID" value="<?php echo $book->Book_ID; ?>">
            <input type="hidden" name="update_old_image" value="<?php echo $book->Thumbnail; ?>">
            <img style="width: 300px;" src="./assets/book_image/<?php echo $book->Thumbnail; ?>" alt="#">
            <br>

            <label style="width: 100%; " for="bookName">Enter book name:</label>
            <input id="bookName" type="text" name="Book_name" class="box" value="<?php echo $book->Book_name; ?>"
                placeholder="enter book name" required>
            <br>

            <label style="width: 100%; " for="genreName">Enter book genre:</label>
            <input id="genreName" type="text" name="Genre_name" class="box" value="<?php echo $chosenGenreList; ?>"
                placeholder="enter book genre" required>

            <label style="width: 100%; " for="authorName">Enter book author:</label>
            <input id="authorName" type="text" name="Author_name" class="box" value="<?php echo $chosenAuthorList; ?>"
                placeholder="enter book author" required>

            <label style="width: 50%; float: left;" for="authorName">Enter original price ($):</label>
            <label style="width: 50%;" for="authorName">Enter discount:</label>

            <input style="width: 50%; float: left;" type="number" min="0" name="O_Price" class="box"
                value="<?php echo $book->O_Price; ?>" placeholder="enter original price" required>

            <input style="width: 50%;" type="number" min="0" max="100" name="Discount" class="box"
                value="<?php echo $book->Discount; ?>" placeholder="enter discount">


            <label style="width: 50%; float: left;" for="authorName">Enter publish year:</label>
            <label style="width: 50%;" for="authorName">Enter Quantity:</label>

            <input style="width: 50%; float: left;" type="number" min="1000" max="<?php echo date("Y"); ?>"
                name="Publish_year" class="box" value="<?php echo $book->Publish_year; ?>"
                placeholder="enter publish year" required>

            <input style="width: 50%;" type="number" min="0" name="Quantity" class="box"
                value="<?php echo $book->Quantity; ?>" placeholder="enter quantity" required>


            <div style="width: 50%; height: 45px; float: left;" class="box">
                <label for="Publisher">Publisher:</label>
                <select id="Publisher" name="Publisher_ID">
                    <?php
                    if (!empty($publisherList)) {
                        foreach ($publisherList as $publisher) {

                            if ($publisher->Publisher_name === $chosenPublisher->Publisher_name) {

                    ?>
                    <option value="<?php echo $publisher->Publisher_ID; ?>" selected>
                        <?php echo $publisher->Publisher_name; ?></option>

                    <?php
                            } else {
                            ?>
                    <option value="<?php echo $publisher->Publisher_ID; ?>">
                        <?php echo $publisher->Publisher_name; ?></option>
                    <?php
                            }
                        }
                    }
                    ?>

                </select>
            </div>

            <input style="width: 50%; height: 45px;" type="file" name="image"
                accept="image/jpg, image/jpeg, image/png, image/webp" class="box">

            <div class="box">
                <label for="descriptionInput">Enter description:</label>
                <textarea name="Description" id="descriptionInput" cols="50"
                    rows="10"><?php echo $book->Description; ?></textarea>
            </div>
            <a href="index.php?controller=adminPages&action=manageBook" name="return" class="btn">Manage Book</a>
            <input onclick="myFunction()" class="option-btn" type="submit" value="update book" name="update_book"
                class="btn">
        </form>

    </section>



    <!-- product CRUD section ends -->

    <!-- show products  -->


    <script>
    function confirmSubmission() {
        // Show a confirmation dialog
        var confirmation = window.confirm('Are you sure you want to submit the form?');

        // Return true to submit the form or false to cancel
        return confirmation;
    }
    </script>



    <!-- custom admin js file link  -->
    <script src="../assets/js/admin_script.js"></script>


</body>

</html>