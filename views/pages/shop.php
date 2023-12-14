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
    <title>shop</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <?php include 'header.php'; ?>

    <div class="heading">
        <h3>our shop</h3>
        <p> <a href="index.php?controller=pages&action=home">home</a> / shop </p>
    </div>

    <section class="search-form">
        <form action="" method="post">
            <input type="text" name="searchInfo" placeholder="search products..." class="box">
            <input type="submit" name="search_book" value="search book" class="btn">
        </form>
    </section>
    <section class="search-form">
        <form action="" method="post">
            <div style="width: 50%; height: 45px; float: left;" class="box">
                <label for="Publisher">Publisher:</label>
                <select id="Publisher" name="Publisher_ID">
                    <option value='0' selected>All</option>
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
                    <option value='0' selected>All</option>
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
                    <option value='0' selected>All</option>
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

            <input type="submit" name="filter_book" value="search" class="btn">
        </form>
    </section>

    <section class="products">

        <!-- <h1 class="title">latest products</h1> -->

        <div class="box-container">

            <?php
            if (!empty($bookList)) {
                foreach ($bookList as $book) {
            ?>
                    <form action="" method="post" class="box">
                        <img style="height: 200px;" class="image" src="../assets/book_image/<?php echo $book->Thumbnail; ?>" alt="">
                        <div class="name"><?php
                                            $Book_name = $book->Book_name;
                                            if (strlen($Book_name) > 20) {
                                                echo substr($Book_name, 0, 20);
                                            } else {
                                                echo $Book_name;
                                            }
                                            ?></div>
                        <div class="price">
                            <div style="text-decoration: line-through; "><?php echo $book->O_Price; ?>$</div>
                            <?php echo $book->Price; ?>$
                        </div>
                        <input type="number" min="1" name="product_quantity" value="1" class="qty">
                        <input type="hidden" name="product_name" value="1">
                        <input type="hidden" name="product_price" value="1">
                        <input type="hidden" name="product_image" value="1">

                        <a href="index.php?controller=pages&action=detail&ID=<?php echo $book->Book_ID; ?>" value="add to cart" name="add_to_cart" class="option-btn">Details</a>
                        <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>

    </section>








    <?php include 'footer.php'; ?>

    <!-- custom js file link  -->
    <script src="../assets/js/script.js"></script>

</body>

</html>