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
    public $book; //class Book

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

    static function getTotalPrice($Cart_ID)
    {
        $db = DB::getInstance();

        $sql = "SELECT SUM(Total_cost) as Total_cost FROM CART_DETAIL WHERE Cart_ID = :Cart_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Cart_ID', $Cart_ID, PDO::PARAM_STR);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $item = $items[0];

        return $item['Total_cost'];
    }

    static function emptyCart($Cart_ID)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM CART_DETAIL WHERE Cart_ID = :Cart_ID";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Cart_ID', $Cart_ID, PDO::PARAM_STR);

        $stmt->execute();
    }
}

class Payment_method
{
    public $Payment_ID;
    public $Payment_name;

    function __construct($Payment_ID, $Payment_name)
    {
        $this->Payment_ID = $Payment_ID;
        $this->Payment_name = $Payment_name;
    }

    static function getPaymentList()
    {
        $db = DB::getInstance();
        $sql = "SELECT* FROM PAYMENT_METHOD";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $list = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                $list[] = new Payment_method($item['Payment_ID'], $item['Payment_name']);
            }
        }

        return $list;
    }

    static function getPaymentMethod($Payment_ID)
    {
        $db = DB::getInstance();

        $sql = "SELECT* FROM PAYMENT_METHOD WHERE Payment_ID = :Payment_ID";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Payment_ID', $Payment_ID, PDO::PARAM_STR);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($items)) {
            $item = $items[0];

            return new Payment_method($item['Payment_ID'], $item['Payment_name']);
        }
    }
}


class Shipping_method
{
    public $Method_ID;
    public $Shipping_name;
    public $Fee;

    function __construct($Method_ID, $Shipping_name, $Fee)
    {
        $this->Method_ID = $Method_ID;
        $this->Shipping_name = $Shipping_name;
        $this->Fee = $Fee;
    }

    static function getShippingList()
    {
        $db = DB::getInstance();
        $sql = "SELECT* FROM SHIPPING_METHOD";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $list = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                $list[] = new Shipping_method($item['Method_ID'], $item['Shipping_name'], $item['Fee']);
            }
        }

        return $list;
    }

    static function getShippingMethod($Method_ID)
    {
        $db = DB::getInstance();

        $sql = "SELECT* FROM SHIPPING_METHOD WHERE Method_ID = :Method_ID";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Method_ID', $Method_ID, PDO::PARAM_STR);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($items)) {
            $item = $items[0];

            return new Shipping_method($item['Method_ID'], $item['Shipping_name'], $item['Fee']);
        }
    }
}

class Order_detail
{
    public $Detail_ID;
    public $Order_ID;
    public $book; //class Book
    public $Price;
    public $Quantity;
    public $Total_cost;

    function __construct($Detail_ID, $Order_ID, $book, $Price, $Quantity, $Total_cost)
    {
        $this->Detail_ID = $Detail_ID;
        $this->Order_ID = $Order_ID;
        $this->book = $book;
        $this->Price = $Price;
        $this->Quantity = $Quantity;
        $this->Total_cost = $Total_cost;
    }

    static function transfer_CartDetail_to_OrderDetail($Cart_detail, $Order_ID) //pass a class Cart_detail
    {
        $db = DB::getInstance();

        $Book_ID = $Cart_detail->book->Book_ID;
        $Price = $Cart_detail->Price;
        $Quantity = $Cart_detail->Quantity;
        $Total_cost = $Cart_detail->Total_cost;



        $sql = "INSERT INTO ORDER_DETAIL (Order_ID, Book_ID, Price, Quantity, Total_cost) VALUES (:Order_ID, :Book_ID, :Price, :Quantity, :Total_cost)";

        $stmt = $db->prepare($sql);

        $params = [
            ':Order_ID' => $Order_ID,
            ':Book_ID' => $Book_ID,
            ':Price' => $Price,
            ':Quantity' => $Quantity,
            ':Total_cost' => $Total_cost
        ];

        $stmt->execute($params);
    }

    static function getOrderDetailList($Order_ID)
    {
        $db = DB::getInstance();

        $sql = "SELECT* FROM ORDER_DETAIL WHERE Order_ID = :Order_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Order_ID', $Order_ID, PDO::PARAM_STR);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $list = [];

        if (!empty($items)) {
            foreach ($items as $item) {
                $book = Book::getBook_useBookID($item['Book_ID']);
                $list[] = new Order_detail($item['Detail_ID'], $item['Order_ID'], $book, $item['Price'], $item['Quantity'], $item['Total_cost']);
            }
        }

        return $list;
    }
}

class Order
{
    public $Order_ID;
    public $Customer_ID;
    public $Address_M;
    public $Create_date;
    public $Status_M;
    public $Total_price;
    public $Note;
    public $Shipping_method; //class Shipping_method
    public $Payment_method; //class Payment_method
    public $detailList; // class Order_detail

    function __construct($Order_ID, $Customer_ID, $Address_M, $Create_date, $Status_M, $Total_price, $Note, $Shipping_method, $Payment_method, $detailList)
    {
        $this->Order_ID = $Order_ID;
        $this->Customer_ID = $Customer_ID;
        $this->Address_M = $Address_M;
        $this->Create_date = $Create_date;
        $this->Status_M = $Status_M;
        $this->Total_price = $Total_price;
        $this->Note = $Note;
        $this->Shipping_method = $Shipping_method;
        $this->Payment_method = $Payment_method;
        $this->detailList = $detailList;
    }


    static function createNewOrder($Customer_ID, $Address_M, $Create_date, $Status_M, $Total_price, $Note, $Shipping_ID, $Payment_ID, $cartInfo) // pass a class Cart
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO ORDER_M (Customer_ID,Address_M,Create_date,Status_M,Total_price,Note,Shipping_ID,Payment_ID) VALUES (:Customer_ID,:Address_M,:Create_date,:Status_M,:Total_price,:Note, :Shipping_ID, :Payment_ID)";

        $stmt = $db->prepare($sql);
        $params = [
            ':Customer_ID' => $Customer_ID,
            ':Address_M' => $Address_M,
            ':Create_date' => $Create_date,
            ':Status_M' => $Status_M,
            ':Total_price' => $Total_price,
            ':Note' => $Note,
            ':Shipping_ID' => $Shipping_ID,
            ':Payment_ID' => $Payment_ID
        ];

        $stmt->execute($params);

        $getOrderID_sql = "SELECT TOP 1 Order_ID FROM ORDER_M ORDER BY Order_ID DESC";
        $getOrderID_stmt = $db->prepare($getOrderID_sql);
        $getOrderID_stmt->execute();

        $Order_ID = $getOrderID_stmt->fetchAll(PDO::FETCH_ASSOC);
        $Order_ID = $Order_ID[0];
        $Order_ID = $Order_ID['Order_ID'];

        $Cart_detail_list = $cartInfo->Cart_detail_list;

        if (!empty($Cart_detail_list)) {
            foreach ($Cart_detail_list as $Cart_detail) {
                Order_detail::transfer_CartDetail_to_OrderDetail($Cart_detail, $Order_ID);
            }
        }
    }

    static function getOrderPlaced($Customer_ID)
    {
        $db = DB::getInstance();

        $sql = "SELECT* FROM ORDER_M WHERE Customer_ID = :Customer_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Customer_ID', $Customer_ID, PDO::PARAM_STR);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $list = [];

        if (!empty($items)) {
            foreach ($items as $item) {

                $detailList = Order_detail::getOrderDetailList($item['Order_ID']);
                $Shipping_method = Shipping_method::getShippingMethod($item['Shipping_ID']);
                $Payment_method = Payment_method::getPaymentMethod($item['Payment_ID']);

                $list[] = new Order($item['Order_ID'], $item['Customer_ID'], $item['Address_M'], $item['Create_date'], $item['Status_M'], $item['Total_price'], $item['Note'], $Shipping_method, $Payment_method, $detailList);
            }
        }

        return $list;
    }

    static function updateOrderStatus($Order_ID, $Status_M)
    {

        $db = DB::getInstance();

        $sql = "UPDATE ORDER_M SET Status_M = :Status_M WHERE Order_ID = :Order_ID";

        $stmt = $db->prepare($sql);

        $params = [
            'Status_M' => $Status_M,
            'Order_ID' => $Order_ID
        ];

        $stmt->execute($params);
    }

    static function getAllOrdersInfo()
    {
        $db = DB::getInstance();

        $sql = "SELECT*FROM ORDER_M";

        $stmt = $db->prepare($sql);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $list = [];

        if (!empty($items)) {
            foreach ($items as $item) {
                $Customer = Customer::findAccount_useAccountID($item['Customer_ID']);
                $Create_date = $item['Create_date'];
                $Address_M = $item['Address_M'];
                $detailList = Order_detail::getOrderDetailList($item['Order_ID']);
                $Total_price = $item['Total_price'];
                $Payment_method = Payment_method::getPaymentMethod($item['Payment_ID']);
                $Shipping_method = Shipping_method::getShippingMethod($item['Shipping_ID']);
                $Order_ID = $item['Order_ID'];
                $Status_M = $item['Status_M'];

                $info = [
                    'Customer' => $Customer,
                    'Create_date' => $Create_date,
                    'Address_M' => $Address_M,
                    'detailList' => $detailList,
                    'Total_price' => $Total_price,
                    'Payment_method' => $Payment_method,
                    'Shipping_method' => $Shipping_method,
                    'Order_ID' => $Order_ID,
                    'Status_M' => $Status_M
                ];

                $list[] = $info;
            }
        }

        return $list;
    }
}
