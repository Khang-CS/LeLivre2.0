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
    <title>cart</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <?php include 'header.php'; ?>

    <div class="heading">
        <h3>shopping cart</h3>
        <p> <a href="./index.php">home</a> / cart </p>
    </div>

    <section class="shopping-cart">

        <h1 class="title">products added</h1>

        <div class="box-container">
            <?php
            $grand_total = 0;
            $Cart_detail_list = $cartInfo->Cart_detail_list;
            if (!empty($Cart_detail_list)) {
                foreach ($Cart_detail_list as $Cart_detail) {
            ?>
                    <div class="box">
                        <img style="height: 200px;" src="../assets/book_image/<?php echo $Cart_detail->book->Thumbnail; ?>" alt="">
                        <div class="name"><?php
                                            $Book_name = $Cart_detail->book->Book_name;
                                            if (strlen($Book_name) > 20) {
                                                echo substr($Book_name, 0, 20);
                                            } else {
                                                echo $Book_name;
                                            }
                                            ?></div>
                        <div class="price"><?php echo $Cart_detail->Price; ?>$</div>
                        <form action="" method="post">
                            <input type="hidden" name="Cart_detail_ID" value="<?php echo $Cart_detail->Cart_detail_ID; ?>">
                            <input type="hidden" name="Price" value="<?php echo $Cart_detail->book->Price; ?>">

                            <input type="number" min="1" name="Quantity" value="<?php echo $Cart_detail->Quantity; ?>">

                            <input type="submit" name="update_cart_detail" value="update" class="option-btn">

                            <input class="fas fa-times" value="X" type="submit" name="delete_cart_detail" onclick="return confirm('delete this from cart?');">

                        </form>
                        <div class="sub-total"> sub total :
                            <span><?php
                                    $subTotal = $Cart_detail->Total_cost;
                                    echo $subTotal; ?>$</span>
                        </div>
                    </div>
            <?php
                    $grand_total += $subTotal;
                }
            } else {
                echo '<p class="empty">your cart is empty</p>';
            }
            ?>
        </div>

        <form action="" method="post" class="box">
            <div style=" margin-top: 2rem; text-align:center;">
                <input type="submit" style="text-align: center;" value="Delete all" name="delete_all" class="delete-btn" onclick="return confirm('delete all from cart?');">
            </div>
        </form>


        <div class="cart-total">
            <p>Grand total : <span><?php echo $grand_total; ?>$</span></p>
            <div class="flex">
                <a href="index.php?controller=pages&action=shop" class="option-btn">continue shopping</a>
                <a href="checkout.php" class="btn">proceed to
                    checkout</a>
            </div>
        </div>

    </section>








    <?php include 'footer.php'; ?>

    <!-- custom js file link  -->
    <script src="../assets/js/script.js"></script>

</body>

</html>