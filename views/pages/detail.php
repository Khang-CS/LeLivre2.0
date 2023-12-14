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

    <style>
    .pTitle {
        font-size: 30px;
    }

    .pInfo {
        font-size: 20px;
    }

    .sale {
        color: red;
    }

    .white {
        background-color: white;
    }

    .qty {
        height: 30px;
        border-radius: 5px;
        border: 1px solid black;
        margin: 10px;
        padding-left: 5px;
        font-size: 20px;
    }

    .title {
        font-size: 30px;
    }
    </style>

</head>

<body>

    <?php include 'header.php'; ?>

    <section class="about">

        <div class="flex">

            <div class="image">
                <img src="../assets/book_image/<?php echo $book->Thumbnail; ?>" alt="">
            </div>

            <form action="" method="post" class="content box">
                <h3><?php echo $book->Book_name; ?></h3>
                <div class="pTitle">Price:</div>
                <div class="pInfo"><?php echo $book->O_Price; ?>$</div>
                <?php if ($book->Discount > 0) {
                ?>
                <div class="pTitle sale">Sale off: <?php echo $book->Discount . "%"; ?></div>
                <div class="pInfo"><?php echo $book->Price; ?>$</div>;
                <?php
                }
                ?>

                <div class="pTitle">Publisher:</div>
                <div class="pInfo"><?php echo $Publisher->Publisher_name; ?></div>

                <div class="pTitle">Author(s):</div>
                <div class="pInfo"><?php echo $authorList; ?></div>

                <div class="pTitle">Genre:</div>
                <div class="pInfo"><?php echo $genreList; ?></div>

                <div class="pTitle">Genre:</div>
                <div class="pInfo"><?php echo $genreList; ?></div>

                <div class="pTitle">Publish year:</div>
                <div class="pInfo"><?php echo $book->Publish_year; ?></div>

                <div class="pTitle">Number of Ratings:</div>
                <div class="pInfo"><?php echo $book->Reviews_N; ?></div>

                <div class="rateStars">

                    <?php
                    $rate = 0;
                    if ($book->Ratings !== '0') {
                        $rate = $book->Ratings;
                    }
                    $nonrate = 5 - $rate;
                    while ($rate !== 0) {
                    ?>
                    <i class="fa-sharp fa-solid fa-star fa-2xl" style="color: #ffc800;"></i>
                    <?php
                        $rate = $rate - 1;
                    }
                    ?>

                    <?php
                    while ($nonrate !== 0) {
                    ?>
                    <i class="fa-sharp fa-regular fa-star fa-2xl" style="color: #ffc800;"></i>
                    <?php
                        $nonrate = $nonrate - 1;
                    }
                    ?>
                </div>

                <input name="Book_ID" type="hidden" value="<?php echo $book->Book_ID; ?>">




                <!-- <i class=" fa-regular fa-star-half-stroke fa-2xl" style="color: #ffc800;"></i> -->

                <p><?php echo $book->Description; ?></p>

                <input type="number" min="1" name="product_quantity" value="1" class="qty">

                <?php
                if ($book->Quantity > 0) {
                ?>
                <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                <?php
                } else {
                ?>

                <div class="pTitle sale">OUT OF STOCK !</div>
                <?php
                } ?>

            </form>

        </div>

    </section>

    <section class="contact">

        <form action="" method="post">
            <h3>say something about this book!</h3>
            <input type="number" min="1" max="5" name="Ratings" value="1" class="box" required>
            <textarea name="Content" class="box" placeholder="enter your message" id="" cols="30" rows="10"
                required></textarea>

            <input type="hidden" name="Account_ID" value="<?php echo $_SESSION['user_id']; ?>">
            <input type="hidden" name="Book_ID" value="<?php echo $book->Book_ID; ?>">
            <input type="submit" value="comment" name="comment" class="btn">
        </form>

    </section>

    <section class="reviews">

        <h1 class="title">Comments</h1>

        <div class="box-container">
            <?php
            if (!empty($reviewList)) {
                foreach ($reviewList as $review) {
            ?>
            <div class="box">
                <img src="../assets/comment_image/User.png" alt="">
                <p><?php echo $review->Content; ?></p>
                <div class="stars">

                    <?php
                            $rate = 0;
                            if ($review->Ratings !== '0') {
                                $rate = $review->Ratings;
                            }
                            $nonrate = 5 - $rate;
                            while ($rate !== 0) {
                            ?>
                    <i class="fa-sharp fa-solid fa-star fa-2xl" style="color: #ffc800;"></i>
                    <?php
                                $rate = $rate - 1;
                            }
                            ?>

                    <?php
                            while ($nonrate !== 0) {
                            ?>
                    <i class="fa-sharp fa-regular fa-star fa-2xl" style="color: #ffc800;"></i>
                    <?php
                                $nonrate = $nonrate - 1;
                            }
                            ?>
                </div>
                <h3>Date: <?php echo $review->Create_date; ?></h3>
                <h3><?php echo $review->Account_name; ?></h3>
            </div>
            <?php
                }
            }
            ?>


        </div>

    </section>





    <?php include 'footer.php'; ?>
    <script src="https://kit.fontawesome.com/de521be8bb.js" crossorigin="anonymous"></script>
    <!-- custom js file link  -->
    <script src="../assets/js/script.js"></script>

</body>

</html>