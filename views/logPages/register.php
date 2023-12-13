<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>



    <?php
    if (isset($message)) {
        echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
    }
    ?>

    <div class="form-container">

        <form action="" method="post">
            <h3>register now</h3>
            <input type="text" name="FName" placeholder="enter your first name" required class="box">
            <input type="text" name="LName" placeholder="enter your last name" required class="box">
            <input type="email" name="Email" placeholder="enter your email" required class="box">
            <input type="tel" name="TelephoneNum" placeholder="enter your phone number" required class="box">
            <input type="date" name="Birthday" placeholder="enter your Birthday" required class="box">
            <input type="text" name="Address_M" placeholder="enter your address" required class="box">
            <input type="text" name="Bank_ID" placeholder="enter your bank account" required class="box">
            <input type="text" name="Bank_name" placeholder="enter your bank name" required class="box">
            <input type="password" name="password" placeholder="enter your password" required class="box">
            <input type="password" name="cpassword" placeholder="confirm your password" required class="box">
            <!-- <select name="user_type" class="box">
         <option value="user">user</option>
         <option value="admin">admin</option>
      </select> -->
            <input type="submit" name="register" value="register now" class="btn">
            <p>already have an account?<a href="index.php?controller=log&action=login">login now</a></p>
        </form>

    </div>

</body>

</html>