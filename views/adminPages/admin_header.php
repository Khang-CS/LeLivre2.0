<?php
if (isset($message)) {
    if (!empty($message)) {
        foreach ($message as $message) {
            echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
        }
    }
}
?>

<header class="header">

    <div class="flex">

        <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>

        <nav class="navbar">
            <a href="index.php?controller=adminPages&action=home">Home</a>
            <a href="index.php?controller=adminPages&action=manageBook">Book</a>
            <a href="index.php?controller=adminPages&action=manageAuthor">Author</a>
            <a href="index.php?controller=adminPages&action=manageGenre">Genre</a>
            <a href="index.php?controller=adminPages&action=managePublisher">Publisher</a>
            <a href="admin_orders.php">Orders</a>
            <a href="admin_users.php">Users</a>
            <a href="admin_users.php">Staffs</a>
            <a href="admin_contacts.php">Messages</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="account-box">
            <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="index.php?controller=log&action=logout" class="delete-btn">logout</a>
        </div>
    </div>

    </div>

</header>