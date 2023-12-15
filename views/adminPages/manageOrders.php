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

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="../assets/css/admin_style.css">

</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="orders">

        <h1 class="title">placed orders</h1>

        <div class="box-container">
            <?php

            if (!empty($orderInfoList)) {
                foreach ($orderInfoList as $order) {
            ?>
                    <div class="box">
                        <p> user id : <span><?php echo $order['Customer']->Account_ID; ?></span> </p>
                        <p> order id : <span><?php echo $order['Order_ID']; ?></span> </p>
                        <p> placed on : <span><?php echo $order['Create_date']; ?></span> </p>
                        <p> name : <span><?php echo $order['Customer']->LName . " " . $order['Customer']->FName; ?></span> </p>

                        <p> Telephone : <span><?php echo $order['Customer']->TelephoneNum; ?></span> </p>
                        <p> email : <span><?php echo $order['Customer']->Email; ?></span> </p>
                        <p> address : <span><?php echo $order['Address_M']; ?></span> </p>

                        <p> products : <span><?php
                                                $detailList = $order['detailList'];
                                                foreach ($detailList as $detail) {
                                                    echo $detail->book->Book_name . "(" . $detail->Quantity . ")-" . $detail->Total_cost . "$, ";
                                                }
                                                ?></span> </p>
                        <p> total price : <span><?php echo $order['Total_price']; ?></span> </p>
                        <p> payment method : <span><?php echo $order['Payment_method']->Payment_name; ?></span> </p>
                        <p> shipping method :
                            <span><?php echo $order['Shipping_method']->Shipping_name . " - " . $order['Shipping_method']->Fee . "$"; ?></span>
                        </p>

                        <p> order status : <span style="color:<?php
                                                                $Status = $order['Status_M'];
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


                        <form action="" method="post">
                            <input type="hidden" name="Order_ID" value="<?php echo $order['Order_ID']; ?>">
                            <select name="Status_M">
                                <option value="0" <?php echo (($Status == 0) ? "selected" : ""); ?>>Processing</option>
                                <option value="1" <?php echo (($Status == 1) ? "selected" : ""); ?>>Delivering</option>
                                <option value="2" <?php echo (($Status == 2) ? "selected" : ""); ?>>Completed</option>
                                <option value="3" <?php echo (($Status == 3) ? "selected" : ""); ?>>Cancelled</option>
                            </select>
                            <input type="submit" value="update" name="update_order" class="option-btn">
                        </form>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no orders placed yet!</p>';
            }
            ?>
        </div>

    </section>










    <!-- custom admin js file link  -->
    <script src="../assets/js/admin_script.js"></script>

</body>

</html>