<header class="header">

    <div class="header-1">
        <div class="flex">
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
            </div>
            <p> new <a href="login.php">login</a> | <a href="register.php">register</a> </p>
        </div>
    </div>

    <div class="header-2">
        <div class="flex">
            <a href="index.php?controller=pages&action=home" class="logo">LeLivre.</a>

            <nav class="navbar">
                <a href="index.php?controller=pages&action=home">home</a>
                <a href="index.php?controller=pages&action=about">about</a>
                <a href="index.php?controller=pages&action=shop">shop</a>
                <a href="index.php?controller=pages&action=contact">contact</a>
                <a href="orders.php">orders</a>
            </nav>

            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <a href="search_page.php" class="fas fa-search"></a>
                <div id="user-btn" class="fas fa-user"></div>
                <?php
                // $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                // $cart_rows_number = mysqli_num_rows($select_cart_number); 
                ?>
                <a href="index.php?controller=pages&action=cart"> <i class="fas fa-shopping-cart"></i>
                    <span>(3)</span> </a>
            </div>

            <div class="user-box">
                <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
                <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
                <a href="index.php?controller=log&action=logout" class="delete-btn">logout</a>
            </div>
        </div>
    </div>

</header>