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
