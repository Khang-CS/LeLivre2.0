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
    <title>orders</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <?php include 'header.php'; ?>

    <div class="heading">
        <h3>your orders</h3>
        <p> <a href="home.php">home</a> / orders </p>
    </div>

    <section class="placed-orders">

        <h1 class="title">placed orders</h1>

        <div class="box-container">

            <?php

            if (!empty($orderPlacedList)) {
                foreach ($orderPlacedList as $order) {
            ?>
                    <form class="box" method="post">
                        <p> placed on : <span><?php echo $order->Create_date; ?></span> </p>
                        <p> name : <span><?php echo $_SESSION['user_name']; ?></span> </p>
                        <p> phone number : <span><?php echo $Customer->TelephoneNum; ?></span> </p>
                        <p> email : <span><?php echo $Customer->Email; ?></span> </p>
                        <p> address : <span><?php echo $order->Address_M; ?></span> </p>
                        <p> payment method : <span><?php echo $order->Payment_method->Payment_name; ?></span> </p>
                        <p> your orders : <span>
                                <?php
                                $detailList = $order->detailList;
                                if (!empty($detailList)) {
                                    foreach ($detailList as $detail) {
                                        echo $detail->book->Book_name . " - " . $detail->Total_cost . "$" . ", ";
                                    }
                                }
                                ?>
                            </span> </p>
                        <p> total price : <span><?php echo $order->Total_price; ?>$</span> </p>
                        <p> payment status : <span style="color:<?php
                                                                $Status = $order->Status_M;
                                                                if ($Status == '2') {
                                                                    echo 'green';
                                                                } else if ($Status == '3') {
                                                                    echo 'red';
                                                                } else {
                                                                    echo 'blue';
                                                                }
                                                                ?>;"><?php
                                                                        if ($Status == '0') {
                                                                            echo 'Processing';
                                                                        } else if ($Status == '1') {
                                                                            echo 'Delivering';
                                                                        } else if ($Status == '2') {
                                                                            echo 'Completed';
                                                                        } else {
                                                                            echo 'Cancelled';
                                                                        }
                                                                        ?></span> </p>
                        <input type="hidden" name="Order_ID" value="<?php echo $order->Order_ID; ?>" class="btn">
                        <?php if ($Status !== '2' && $Status !== '3') {
                        ?>
                            <input type="submit" name="received" value="received the order" class="btn">
                            <input type="submit" name="cancel" value="cancel the order" class="delete-btn">
                        <?php
                        }
                        ?>
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">no orders placed yet!</p>';
            }
            ?>
        </div>

    </section>








    <?php include 'footer.php'; ?>

    <!-- custom js file link  -->
    <script src="../assets/js/script.js"></script>

</body>

</html>