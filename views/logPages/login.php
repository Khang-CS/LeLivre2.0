<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>

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
            <h3>login now</h3>
            <input type="email" name="Email" placeholder="enter your email" required class="box">
            <input type="password" name="H_Password" placeholder="enter your password" required class="box">
            <input type="submit" name="login" value="login now" class="btn">
            <p>don't have an account? <a href="index.php?controller=log&action=register">register now</a></p>
        </form>

    </div>

</body>

</html>