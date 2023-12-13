<?php


class Admin
{
}

class Author
{
    public $Author_name;
    public $Author_ID;

    function __construct($Author_name, $Author_ID)
    {
        $this->Author_name = $Author_name;
        $this->Author_ID = $Author_ID;
    }

    static function addAuthor($Author_name)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO AUTHOR (Author_name) VALUES (:Author_name)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Author_name', $Author_name, PDO::PARAM_STR);
        $stmt->execute();
    }

    static function getAuthorList()
    {
        $list = [];
        $db = DB::getInstance();
        $sql = "SELECT* FROM AUTHOR";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as $item) {
            $list[] = new Author($item['Author_name'], $item['Author_ID']);
        }

        return $list;
    }

    static function updateAuthor($Author_ID, $Author_name)
    {
        $db = DB::getInstance();
        $sql = "UPDATE AUTHOR SET Author_name = :Author_name WHERE Author_ID = :Author_ID";

        $stmt = $db->prepare($sql);

        $params = [
            ':Author_ID' => $Author_ID,
            ':Author_name' => $Author_name
        ];

        $stmt->execute($params);
    }

    static function deleteAuthor($Author_ID)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM AUTHOR WHERE Author_ID = :Author_ID";

        $stmt = $db->prepare($sql);

        $params = [
            ':Author_ID' => $Author_ID
        ];

        $stmt->execute($params);
    }

    static function getAuthorID_useName($Author_name)
    {
        $db = DB::getInstance();
        $sql = "SELECT Author_ID FROM AUTHOR WHERE Author_name = :Author_name";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Author_name', $Author_name, PDO::PARAM_STR);

        $stmt->execute();

        $Author_ID = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($Author_ID)) {
            $Author_ID = $Author_ID[0];

            return $Author_ID['Author_ID'];
        }

        return 0;
    }


    static function insertWrite($Author_ID, $Book_ID)
    {
        $db = DB::getInstance();

        $sql = "INSERT INTO WRITE (Author_ID, Book_ID) VALUES (:Author_ID, :Book_ID)";

        $stmt = $db->prepare($sql);
        $params = [
            'Author_ID' => $Author_ID,
            'Book_ID' => $Book_ID
        ];

        $stmt->execute($params);
    }

    static function deleteWrite($Book_ID)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM WRITE WHERE Book_ID = :Book_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Book_ID', $Book_ID, PDO::PARAM_STR);

        $stmt->execute();
    }

    static function getAuthorList_useBookID($Book_ID)
    {

        $list = [];
        $db = DB::getInstance();
        $sql = "SELECT AUTHOR.Author_ID, AUTHOR.Author_name FROM BOOK INNER JOIN WRITE ON BOOK.Book_ID = WRITE.Book_ID INNER JOIN AUTHOR ON WRITE.Author_ID = AUTHOR.Author_ID WHERE BOOK.Book_ID= :Book_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Book_ID', $Book_ID, PDO::FETCH_ASSOC);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($items)) {
            foreach ($items as $item) {
                $list[] = new Author($item['Author_name'], $item['Author_ID']);
            }
        }

        return $list;
    }
}


class Genre
{
    public $Genre_name;
    public $Genre_ID;

    function __construct($Genre_name, $Genre_ID)
    {
        $this->Genre_name = $Genre_name;
        $this->Genre_ID = $Genre_ID;
    }

    static function addGenre($Genre_name)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO GENRE (Genre_name) VALUES (:Genre_name)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Genre_name', $Genre_name, PDO::PARAM_STR);
        $stmt->execute();
    }

    static function getGenreList()
    {
        $list = [];
        $db = DB::getInstance();
        $sql = "SELECT* FROM GENRE";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as $item) {
            $list[] = new Genre($item['Genre_name'], $item['Genre_ID']);
        }

        return $list;
    }

    static function updateGenre($Genre_ID, $Genre_name)
    {
        $db = DB::getInstance();
        $sql = "UPDATE GENRE SET Genre_name = :Genre_name WHERE Genre_ID = :Genre_ID";

        $stmt = $db->prepare($sql);

        $params = [
            ':Genre_ID' => $Genre_ID,
            ':Genre_name' => $Genre_name
        ];

        $stmt->execute($params);
    }

    static function deleteGenre($Genre_ID)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM GENRE WHERE Genre_ID = :Genre_ID";

        $stmt = $db->prepare($sql);

        $params = [
            ':Genre_ID' => $Genre_ID
        ];

        $stmt->execute($params);
    }

    static function getGenreID_useName($Genre_name)
    {
        $db = DB::getInstance();
        $sql = "SELECT Genre_ID FROM GENRE WHERE Genre_name = :Genre_name";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Genre_name', $Genre_name, PDO::PARAM_STR);

        $stmt->execute();

        $Genre_ID = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($Genre_ID)) {
            $Genre_ID = $Genre_ID[0];

            return $Genre_ID['Genre_ID'];
        }

        return 0;
    }

    static function insertBelongsTo($Genre_ID, $Book_ID)
    {
        $db = DB::getInstance();

        $sql = "INSERT INTO BELONGS_TO (Genre_ID, Book_ID) VALUES (:Genre_ID, :Book_ID)";

        $stmt = $db->prepare($sql);
        $params = [
            'Genre_ID' => $Genre_ID,
            'Book_ID' => $Book_ID
        ];

        $stmt->execute($params);
    }

    static function deleteBelongsTo($Book_ID)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM BELONGS_TO WHERE Book_ID = :Book_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Book_ID', $Book_ID, PDO::PARAM_STR);

        $stmt->execute();
    }

    static function getGenreList_useBookID($Book_ID)
    {

        $list = [];
        $db = DB::getInstance();
        $sql = "SELECT GENRE.Genre_ID, GENRE.Genre_name FROM BOOK INNER JOIN BELONGS_TO ON BOOK.Book_ID = BELONGS_TO.Book_ID INNER JOIN GENRE ON BELONGS_TO.Genre_ID = GENRE.Genre_ID WHERE BOOK.Book_ID= :Book_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Book_ID', $Book_ID, PDO::FETCH_ASSOC);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($items)) {
            foreach ($items as $item) {
                $list[] = new Genre($item['Genre_name'], $item['Genre_ID']);
            }
        }
        return $list;
    }
}

class Publisher
{
    public $Publisher_name;
    public $Publisher_ID;

    function __construct($Publisher_name, $Publisher_ID)
    {
        $this->Publisher_name = $Publisher_name;
        $this->Publisher_ID = $Publisher_ID;
    }

    static function addPublisher($Publisher_name)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO PUBLISHER (Publisher_name) VALUES (:Publisher_name)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Publisher_name', $Publisher_name, PDO::PARAM_STR);
        $stmt->execute();
    }

    static function getPublisherList()
    {
        $list = [];
        $db = DB::getInstance();
        $sql = "SELECT* FROM PUBLISHER";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as $item) {
            $list[] = new Publisher($item['Publisher_name'], $item['Publisher_ID']);
        }

        return $list;
    }

    static function updatePublisher($Publisher_ID, $Publisher_name)
    {
        $db = DB::getInstance();
        $sql = "UPDATE PUBLISHER SET Publisher_name = :Publisher_name WHERE Publisher_ID = :Publisher_ID";

        $stmt = $db->prepare($sql);

        $params = [
            ':Publisher_ID' => $Publisher_ID,
            ':Publisher_name' => $Publisher_name
        ];

        $stmt->execute($params);
    }

    static function deletePublisher($Publisher_ID)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM PUBLISHER WHERE Publisher_ID = :Publisher_ID";

        $stmt = $db->prepare($sql);

        $params = [
            ':Publisher_ID' => $Publisher_ID
        ];

        $stmt->execute($params);
    }

    static function insertPublish($Publisher_ID, $Book_ID)
    {
        $db = DB::getInstance();

        $sql = "INSERT INTO PUBLISH (Publisher_ID, Book_ID) VALUES (:Publisher_ID, :Book_ID)";

        $stmt = $db->prepare($sql);
        $params = [
            'Publisher_ID' => $Publisher_ID,
            'Book_ID' => $Book_ID
        ];

        $stmt->execute($params);
    }

    static function deletePublish($Book_ID)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM PUBLISH WHERE Book_ID = :Book_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Book_ID', $Book_ID, PDO::PARAM_STR);

        $stmt->execute();
    }

    static function getPublisher_useBookID($Book_ID)
    {

        $db = DB::getInstance();
        $sql = "SELECT PUBLISHER.Publisher_ID, PUBLISHER.Publisher_name FROM PUBLISHER INNER JOIN PUBLISH ON PUBLISHER.Publisher_ID = PUBLISH.Publisher_ID INNER JOIN BOOK ON PUBLISH.Book_ID = BOOK.Book_ID WHERE BOOK.Book_ID = :Book_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Book_ID', $Book_ID, PDO::PARAM_STR);

        $stmt->execute();

        $item = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $item = $item[0];


        return new Publisher($item['Publisher_name'], $item['Publisher_ID']);
    }
}

class Book
{
    public $Book_ID, $Book_name, $O_Price, $Discount, $Price, $Publish_year, $Ratings, $Thumbnail, $Reviews_N, $Quantity, $Deleted, $Description;

    function __construct($Book_ID, $Book_name, $O_Price, $Discount, $Price, $Publish_year, $Ratings, $Thumbnail, $Reviews_N, $Quantity, $Deleted, $Description)
    {
        $this->Book_ID = $Book_ID;
        $this->Book_name = $Book_name;
        $this->O_Price = $O_Price;
        $this->Discount = $Discount;
        $this->Price = $Price;
        $this->Publish_year = $Publish_year;
        $this->Ratings = $Ratings;
        $this->Thumbnail = $Thumbnail;
        $this->Reviews_N = $Reviews_N;
        $this->Quantity = $Quantity;
        $this->Deleted = $Deleted;
        $this->Description = $Description;
    }


    static function getBookList()
    {
        $list = [];
        $db = DB::getInstance();
        $sql = "SELECT * FROM BOOK";

        $stmt = $db->prepare($sql);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $item) {
            $list[] = new Book($item['Book_ID'], $item['Book_name'], $item['O_Price'], $item['Discount'], $item['Price'], $item['Publish_year'], $item['Ratings'], $item['Thumbnail'], $item['Reviews_N'], $item['Quantity'], $item['Deleted'], $item['Description']);
        }

        return $list;
    }

    static function addBook($Book_name, $O_Price, $Discount, $Publish_year, $Thumbnail, $Quantity, $Description)
    {
        $db = DB::getInstance();

        $sql = "INSERT INTO BOOK (Book_name, O_Price, Discount, Publish_year, Thumbnail, Quantity, Description) VALUES (:Book_name, :O_Price, :Discount, :Publish_year, :Thumbnail, :Quantity, :Description)";

        $params = [
            ':Book_name' => $Book_name,
            ':O_Price' => $O_Price,
            ':Discount' => $Discount,
            ':Publish_year' => $Publish_year,
            ':Thumbnail' => $Thumbnail,
            ':Quantity' => $Quantity,
            ':Description' => $Description
        ];

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        $Book_ID_sql = "SELECT TOP 1 Book_ID FROM BOOK ORDER BY Book_ID DESC";
        $Book_ID_stmt = $db->prepare($Book_ID_sql);
        $Book_ID_stmt->execute();

        $Book_ID = $Book_ID_stmt->fetchAll(PDO::FETCH_ASSOC);

        $Book_ID = $Book_ID[0];

        return $Book_ID['Book_ID'];
    }

    static function deleteBook($Book_ID)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM BOOK WHERE Book_ID = :Book_ID";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Book_ID', $Book_ID, PDO::PARAM_STR);

        $stmt->execute();
    }

    static function getBook_useBookID($Book_ID)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM BOOK WHERE Book_ID = :Book_ID";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':Book_ID', $Book_ID, PDO::PARAM_STR);

        $stmt->execute();

        $item = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $item = $item[0];

        return new Book($item['Book_ID'], $item['Book_name'], $item['O_Price'], $item['Discount'], $item['Price'], $item['Publish_year'], $item['Ratings'], $item['Thumbnail'], $item['Reviews_N'], $item['Quantity'], $item['Deleted'], $item['Description']);
    }

    static function updateBook($Book_ID, $Book_name, $O_Price, $Discount, $Publish_year, $Thumbnail, $Quantity, $Description)
    {
        $db = DB::getInstance();

        $sql = "UPDATE BOOK SET Book_name = :Book_name, O_Price = :O_Price, Discount = :Discount, Publish_year = :Publish_year, Thumbnail = :Thumbnail, Quantity = :Quantity, Description = :Description WHERE Book_ID = :Book_ID";

        $stmt = $db->prepare($sql);
        $params = [
            ':Book_name' => $Book_name,
            ':O_Price' => $O_Price,
            ':Discount' => $Discount,
            ':Publish_year' => $Publish_year,
            ':Thumbnail' => $Thumbnail,
            ':Quantity' => $Quantity,
            ':Description' => $Description,
            ':Book_ID' => $Book_ID
        ];

        $stmt->execute($params);
    }
}
