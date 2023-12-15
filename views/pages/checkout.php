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
    <title>checkout</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <?php include 'header.php'; ?>

    <div class="heading">
        <h3>checkout</h3>
        <p> <a href="home.php">home</a> / checkout </p>
    </div>

    <section class="display-order">

        <?php
        $grand_total = 0;
        $Cart_detail_list = $cartInfo->Cart_detail_list;
        if (!empty($Cart_detail_list)) {
            foreach ($Cart_detail_list as $Cart_detail) {
        ?>
                <p> <?php echo $Cart_detail->book->Book_name; ?>
                    <span>(<?php echo $Cart_detail->Price; ?>$ x <?php echo $Cart_detail->Quantity; ?> =
                        <?php echo $Cart_detail->Total_cost; ?>$)</span>
                </p>
        <?php
            }
        } else {
            echo '<p class="empty">your cart is empty</p>';
        }
        ?>
        <div class="grand-total"> grand total : <span>$<?php echo $grand_total; ?>/-</span> </div>

    </section>

    <section class="checkout">

        <form action="" method="post">
            <h3>place your order</h3>
            <div class="flex">
                <div class="inputBox">
                    <span>your first name :</span>
                    <input type="text" name="FName" value="<?php echo $customerInfo->FName; ?>" disabled>
                </div>
                <div class="inputBox">
                    <span>your last name :</span>
                    <input type="text" name="LName" value="<?php echo $customerInfo->LName; ?>" disabled>
                </div>
                <div class="inputBox">
                    <span>your number :</span>
                    <input type="number" name="TelephoneNum" value="<?php echo $customerInfo->TelephoneNum; ?>" disabled>
                </div>
                <div class="inputBox">
                    <span>your email :</span>
                    <input type="email" name="Email" value="<?php echo $customerInfo->Email; ?>" disabled>
                </div>
                <div class="inputBox">
                    <span>payment method :</span>
                    <select name="Payment_ID">
                        <?php
                        if (!empty($paymentList)) {
                            foreach ($paymentList as $payment) {
                        ?>
                                <option value="<?php echo $payment->Payment_ID; ?>"><?php echo $payment->Payment_name; ?>
                                </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="inputBox">
                    <span>Shipping method :</span>
                    <select name="Method_ID">
                        <?php
                        if (!empty($shippingList)) {
                            foreach ($shippingList as $shipping) {
                        ?>
                                <option value="<?php echo $shipping->Method_ID; ?>">
                                    <?php echo $shipping->Shipping_name . " - " . $shipping->Fee; ?>$
                                </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="inputBox">
                    <span>Address :</span>
                    <input type="text" name="Address_M" required placeholder="enter your delivery address" value="<?php echo $customerInfo->Address_M; ?>">
                </div>

                <input type="hidden" name="Cart_ID" value="<?php echo $cartInfo->Cart_ID; ?>">

                <div class="inputBox">
                    <textarea name="Note" placeholder="enter your message" id="" cols="57" rows="10"></textarea>
                </div>



            </div>
            <input type="submit" value="order now" class="btn" name="order">
        </form>

    </section>









    <?php include 'footer.php'; ?>

    <!-- custom js file link  -->
    <script src="../assets/js/script.js"></script>

</body>

</html>