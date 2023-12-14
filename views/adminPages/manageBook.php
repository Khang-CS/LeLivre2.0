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
    <title>manage books</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="../assets/css/admin_style.css">

</head>

<body>

    <?php include 'admin_header.php'; ?>

    <!-- product CRUD section starts  -->

    <section class="add-products">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Search book</h3>
            <input type="text" name="searchInfo" class="box" placeholder="search book...." required>
            <input type="submit" value="search" name="search_book" class="btn">
        </form>
    </section>

    <section class="add-products">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Filter</h3>
            <div style="width: 50%; height: 45px; float: left;" class="box">
                <label for="Publisher">Publisher:</label>
                <select id="Publisher" name="Publisher_ID">
                    <?php if (!empty($publisherList)) {
                        foreach ($publisherList as $publisher) {
                    ?>
                            <option value="<?php echo $publisher->Publisher_ID; ?>">
                                <?php $Publisher_name = $publisher->Publisher_name;
                                if (strlen($Publisher_name) > 16) {
                                    echo substr($Publisher_name, 0, 16);
                                } else {
                                    echo $Publisher_name;
                                }
                                ?>
                            </option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <div style="width: 50%; height: 45px; float: left;" class="box">
                <label for="Genre">Genre:</label>
                <select id="Genre" name="Genre_ID">
                    <?php if (!empty($genreList)) {
                        foreach ($genreList as $genre) {
                    ?>
                            <option value="<?php echo $genre->Genre_ID; ?>">
                                <?php $Genre_name = $genre->Genre_name;
                                if (strlen($Genre_name) > 16) {
                                    echo substr($Genre_name, 0, 16);
                                } else {
                                    echo $Genre_name;
                                }
                                ?>
                            </option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <div style="width: 50%; height: 45px; float: left;" class="box">
                <label for="Author">Author:</label>
                <select id="Author" name="Author_ID">
                    <?php if (!empty($authorList)) {
                        foreach ($authorList as $author) {
                    ?>
                            <option value="<?php echo $author->Author_ID; ?>">
                                <?php $Author_name = $author->Author_name;
                                if (strlen($Author_name) > 16) {
                                    echo substr($Author_name, 0, 16);
                                } else {
                                    echo $Author_name;
                                }
                                ?>
                            </option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <input type="submit" value="Filter" name="filter_book" class="btn">
        </form>
    </section>

    <section class="add-products">

        <h1 class="title">manage books</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <h3>add book</h3>
            <input type="text" name="Book_name" class="box" placeholder="enter book name" required>
            <input type="text" name="Genre_name" class="box" placeholder="enter book genre" required>
            <input type="text" name="Author_name" class="box" placeholder="enter book author" required>
            <input style="width: 50%; float: left;" type="number" min="0" name="O_Price" class="box" placeholder="enter original price" required>
            <input style="width: 50%;" type="number" min="0" max="100" name="Discount" class="box" placeholder="enter discount">


            <input style="width: 50%; float: left;" type="number" min="1000" max="<?php echo date("Y"); ?>" name="Publish_year" class="box" placeholder="enter publish year" required>
            <input style="width: 50%;" type="number" min="0" name="Quantity" class="box" placeholder="enter quantity" required>
            <div style="width: 50%; height: 45px; float: left;" class="box">
                <label for="Publisher">Publisher:</label>
                <select id="Publisher" name="Publisher_ID">
                    <?php
                    if (!empty($publisherList)) {
                        foreach ($publisherList as $publisher) {
                    ?>
                            <option value="<?php echo $publisher->Publisher_ID; ?>">
                                <?php echo $publisher->Publisher_name; ?></option>
                    <?php }
                    }
                    ?>
                </select>
            </div>

            <input style="width: 50%; height: 45px;" type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>

            <div class="box">
                <label for="descriptionInput">Enter description:</label>
                <textarea name="Description" id="descriptionInput" cols="50" rows="10"></textarea>
            </div>
            <input type="submit" value="add book" name="add_book" class="btn">
        </form>

    </section>



    <!-- product CRUD section ends -->

    <!-- show products  -->
    <section class="show-products">

        <div class="box-container">
            <?php
            if (!empty($bookList)) {
                foreach ($bookList as $book) {
            ?>
                    <form action="" method="post" enctype="multipart/form-data" class="box">
                        <img style="height: 200px;" src="../assets/book_image/<?php echo $book->Thumbnail; ?>" alt="">
                        <input type="text" name="Book_ID" value="<?php echo $book->Book_ID; ?>" hidden>
                        <input type="text" name="Book_name" value="<?php echo $book->Book_name; ?>" hidden>
                        <input type="text" name="Thumbnail" value="<?php echo $book->Thumbnail; ?>" hidden>
                        <div class="name"><?php echo substr($book->Book_name, 0, 20); ?>
                        </div>
                        <div class="price">
                            <div style="text-decoration: line-through;">$<?php echo $book->O_Price; ?></div>
                            /$<?php echo $book->Price; ?>
                        </div>
                        <a href="index.php?controller=adminPages&action=manageBookDetail&update=<?php echo $book->Book_ID; ?>" class="option-btn">update</a>
                        <input type="submit" value="delete" name="delete_book" class="delete-btn" onclick="return confirm('delete this book?');">
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>

        </div>

    </section>




    <!-- custom admin js file link  -->
    <script src="../assets/js/admin_script.js"></script>


</body>

</html>