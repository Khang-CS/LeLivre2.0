<?php

class Customer extends Account
{
    public $Bank_ID, $Bank_name;

    function __construct($Account_ID, $FName, $LName, $Email, $TelephoneNum, $H_Password, $Birthday, $Address_M, $Deleted, $Bank_ID, $Bank_name)
    {
        Account::__construct($Account_ID, $FName, $LName, $Email, $TelephoneNum, $H_Password, $Birthday, $Address_M, $Deleted);

        $this->Bank_ID = $Bank_ID;
        $this->Bank_name = $Bank_name;
    }

    static function findAccount_useAccountID($Account_ID)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM ACCOUNT, CUSTOMER WHERE ACCOUNT.Account_ID = CUSTOMER.Customer_ID AND ACCOUNT.Account_ID = :Account_ID";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Account_ID', $Account_ID, PDO::PARAM_STR);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $item = $items[0];

        return new Customer($item['Account_ID'], $item['FName'], $item['LName'], $item['Email'], $item['TelephoneNum'], $item['H_Password'], $item['Birthday'], $item['Address_M'], $item['Deleted'], $item['Bank_ID'], $item['Bank_name']);
    }
}

class Reviews
{
    public $Create_date, $Account_ID, $Book_ID, $Content, $Ratings, $Img, $Account_name, $Book_name;

    function __construct($Create_date, $Account_ID, $Book_ID, $Content, $Ratings, $Img)
    {
        $this->Create_date = $Create_date;
        $this->Account_ID = $Account_ID;
        $this->Book_ID = $Book_ID;
        $this->Content = $Content;
        $this->Ratings = $Ratings;
        $this->Img = $Img;

        $Account_name = Customer::findAccount_useAccountID($Account_ID);
        $Account_name = $Account_name->FName . " " . $Account_name->LName;

        $book = Book::getBook_useBookID($Book_ID);
        $Book_name = $book->Book_name;

        $this->Book_name = $Book_name;

        $this->Account_name = $Account_name;
    }

    static function updateNumberOfReviews($Book_ID)
    {
        $db = DB::getInstance();

        // AGGREGATE QUERY
        $sql = "SELECT COUNT(*) AS row_count FROM REVIEWS WHERE Book_ID = :Book_ID";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Book_ID', $Book_ID);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $item = $items[0];

        $Reviews_N = $item['row_count'];

        $sqlUpdate = "UPDATE BOOK SET Reviews_N = :Reviews_N WHERE Book_ID = :Book_ID";
        $stmtUpdate = $db->prepare($sqlUpdate);

        $updateParams = [
            ':Reviews_N' => $Reviews_N,
            ':Book_ID' => $Book_ID
        ];

        $stmtUpdate->execute($updateParams);
    }

    static function updateAverageRatings($Book_ID)
    {

        $db = DB::getInstance();
        // AGGREGATE QUERY
        $sql = "SELECT AVG(Ratings) AS average_value FROM REVIEWS WHERE Book_ID = :Book_ID";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Book_ID', $Book_ID);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $item = $items[0];

        $Ratings = $item['average_value'];

        $sqlUpdate = "UPDATE BOOK SET Ratings = :Ratings WHERE Book_ID = :Book_ID";
        $stmtUpdate = $db->prepare($sqlUpdate);

        $updateParams = [
            ':Ratings' => $Ratings,
            ':Book_ID' => $Book_ID
        ];

        $stmtUpdate->execute($updateParams);
    }

    static function insertReview($Create_date, $Account_ID, $Book_ID, $Content, $Ratings, $Img)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO REVIEWS (Create_date,Account_ID, Book_ID, Content, Ratings, Img) VALUES (:Create_date, :Account_ID, :Book_ID, :Content,:Ratings,:Img)";

        $stmt = $db->prepare($sql);

        $params = [
            ':Create_date' => $Create_date,
            ':Account_ID' => $Account_ID,
            ':Book_ID' => $Book_ID,
            ':Content' => $Content,
            ':Ratings' => $Ratings,
            ':Img' => $Img
        ];

        $stmt->execute($params);
        Reviews::updateNumberOfReviews($Book_ID);
        Reviews::updateAverageRatings($Book_ID);
    }

    static function getReviews_useBook_ID($Book_ID)
    {
        $db = DB::getInstance();
        $sql = "SELECT* FROM REVIEWS WHERE Book_ID = :Book_ID";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Book_ID', $Book_ID, PDO::PARAM_STR);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $list = [];

        foreach ($items as $item) {
            $list[] = new Reviews($item['Create_date'], $item['Account_ID'], $item['Book_ID'], $item['Content'], $item['Ratings'], $item['Img']);
        }

        return $list;
    }
}

class Cart_detail
{
    public $Cart_detail_ID;
    public $Price;
    public $Quantity;
    public $Total_cost;
    public $Cart_ID;
    public $book;

    function __construct($Cart_detail_ID, $Price, $Quantity, $Total_cost, $Cart_ID, $book)
    {
        $this->Cart_detail_ID = $Cart_detail_ID;
        $this->Price = $Price;
        $this->Quantity = $Quantity;
        $this->Total_cost = $Total_cost;
        $this->$Cart_ID = $Cart_ID;
        $this->book = $book; //class Book
    }

    static function checkIfBookIsAdded($Cart_ID, $Book_ID)
    {
        $db = DB::getInstance();
        $sql = "SELECT Cart_detail_ID FROM CART_DETAIL WHERE Cart_ID = :Cart_ID AND Book_ID = :Book_ID";

        $stmt = $db->prepare($sql);
        $params = [
            'Cart_ID' => $Cart_ID,
            'Book_ID' => $Book_ID
        ];
        $stmt->execute($params);

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($items)) {
            $item = $items[0];
            return $item['Cart_detail_ID'];
        }

        return 0;
    }

    static function getCartDetail_useCartDetailID($Cart_detail_ID)
    {
        $db = DB::getInstance();
        $sql = "SELECT* FROM CART_DETAIL WHERE Cart_detail_ID = :Cart_detail_ID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Cart_detail_ID', $Cart_detail_ID, PDO::PARAM_STR);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $item = $items[0];

        $book = Book::getBook_useBookID($item['Book_ID']);

        return new Cart_detail($item['Cart_detail_ID'], $item['Price'], $item['Quantity'], $item['Total_cost'], $item['Cart_ID'], $book);
    }

    static function createNewCartDetail($Price, $Quantity, $Cart_ID, $Book_ID)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO CART_DETAIL (Price, Quantity, Cart_ID, Book_ID) VALUES (:Price, :Quantity, :Cart_ID, :Book_ID)";

        $stmt = $db->prepare($sql);

        $params = [
            'Price' => $Price,
            'Quantity' => $Quantity,
            'Cart_ID' => $Cart_ID,
            'Book_ID' => $Book_ID
        ];

        $stmt->execute($params);
    }

    static function updateCartDetail($Cart_detail_ID, $Price, $Quantity)
    {
        $db = DB::getInstance();
        $sql = "UPDATE CART_DETAIL SET Price = :Price, Quantity = :Quantity WHERE Cart_detail_ID = :Cart_detail_ID";

        $stmt = $db->prepare($sql);

        $params = [
            'Cart_detail_ID' => $Cart_detail_ID,
            'Price' => $Price,
            'Quantity' => $Quantity
        ];

        $stmt->execute($params);
    }

    static function getCartDetailList($Customer_ID)
    {
        $db = DB::getInstance();
        $sql = "SELECT Cart_detail_ID FROM CART INNER JOIN CART_DETAIL ON CART.Cart_ID = CART_DETAIL.Cart_ID WHERE CART.Customer_ID = :Customer_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Customer_ID', $Customer_ID, PDO::PARAM_STR);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $list = [];

        if (!empty($items)) {
            foreach ($items as $item) {
                $list[] = Cart_detail::getCartDetail_useCartDetailID($item['Cart_detail_ID']);
            }
        }

        return $list;
    }

    static function deleteCartDetail($Cart_detail_ID)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM CART_DETAIL WHERE Cart_detail_ID = :Cart_detail_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Cart_detail_ID', $Cart_detail_ID, PDO::PARAM_STR);

        $stmt->execute();
    }
}

class Cart
{
    public $Cart_ID;
    public $Customer_ID;
    public $Cart_detail_list;

    function __construct($Customer_ID, $Cart_detail_list)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM CART WHERE Customer_ID = :Customer_ID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Customer_ID', $Customer_ID, PDO::PARAM_STR);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::PARAM_STR);
        if (!empty($items)) {
            $item = $items[0];
            $Cart_ID = $item['Cart_ID'];
            $this->Cart_ID = $Cart_ID;
        } else {
            $newCart_sql = "INSERT INTO CART(Customer_ID) VALUES (:Customer_ID)";
            $newCart_stmt = $db->prepare($newCart_sql);
            $newCart_stmt->bindParam(':Customer_ID', $Customer_ID, PDO::PARAM_STR);
            $newCart_stmt->execute();

            $sql = "SELECT * FROM CART WHERE Customer_ID = :Customer_ID";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':Customer_ID', $Customer_ID, PDO::PARAM_STR);
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::PARAM_STR);
            $item = $items[0];

            $Cart_ID = $item['Cart_ID'];
            $this->Cart_ID = $Cart_ID;
        }

        $this->Customer_ID = $Customer_ID;
        $this->Cart_detail_list = $Cart_detail_list;
    }

    static function createNewCart($Customer_ID)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO CART (Customer_ID) VALUES (:Customer_ID)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Customer_ID', $Customer_ID, PDO::PARAM_STR);
        $stmt->execute();
    }

    static function getCartID_useCustomerID($Customer_ID)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM CART WHERE Customer_ID = :Customer_ID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Customer_ID', $Customer_ID, PDO::PARAM_STR);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::PARAM_STR);

        if (!empty($items)) {
            $item = $items[0];

            return $item['Cart_ID'];
        }

        Cart::createNewCart($Customer_ID);

        return Cart::getCartID_useCustomerID($Customer_ID);
    }

    static function addBookToCart($Customer_ID, $Price, $Quantity, $Book_ID)
    {
        $Cart_ID = Cart::getCartID_useCustomerID($Customer_ID);

        $checkExist = Cart_detail::checkIfBookIsAdded($Cart_ID, $Book_ID);

        if ($checkExist) {
            $Cart_detail_ID = $checkExist;
            $Cart_detail_old = Cart_detail::getCartDetail_useCartDetailID($Cart_detail_ID);

            $newPrice = $Price;
            $newQuantity = $Cart_detail_old->Quantity + $Quantity;

            Cart_detail::updateCartDetail($Cart_detail_ID, $newPrice, $newQuantity);
        }
        // If book was never added
        else {
            Cart_detail::createNewCartDetail($Price, $Quantity, $Cart_ID, $Book_ID);
        }
    }

    static function getCart($Customer_ID)
    {
        $Cart_detail_list = Cart_detail::getCartDetailList($Customer_ID);

        return new Cart($Customer_ID, $Cart_detail_list);
    }
}
