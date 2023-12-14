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
    <title>home</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <?php include 'header.php'; ?>

    <section class="home">

        <div class="content">
            <h3>Hand Picked Book to your door.</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi, quod? Reiciendis ut porro iste totam.
            </p>
            <a href="about.php" class="white-btn">discover more</a>
        </div>

    </section>

    <section class="products">

        <h1 class="title">latest products</h1>

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
                        <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>


        </div>

        <div class="load-more" style="margin-top: 2rem; text-align:center">
            <a href="shop.php" class="option-btn">load more</a>
        </div>

    </section>

    <section class="about">

        <div class="flex">

            <div class="image">
                <img src="https://cdn0.thedailyeco.com/en/posts/3/9/0/what_are_jungle_ecosystems_93_600.jpg" alt="">
            </div>

            <div class="content">
                <h3>about us</h3>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Impedit quos enim minima ipsa dicta officia
                    corporis ratione saepe sed adipisci?</p>
                <a href="about.php" class="btn">read more</a>
            </div>

        </div>

    </section>

    <section class="home-contact">

        <div class="content">
            <h3>have any questions?</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Atque cumque exercitationem repellendus, amet
                ullam voluptatibus?</p>
            <a href="contact.php" class="white-btn">contact us</a>
        </div>

    </section>





    <?php include 'footer.php'; ?>

    <!-- custom js file link  -->
    <script src="../assets/js/script.js"></script>

</body>

</html>