<?php
require_once('controllers/base_controller.php');
require_once('models/admin.php');
require_once('models/customer_model.php');
// require_once('models/admin.php');

class PagesController extends BaseController
{
    function __construct()
    {
        $this->folder = 'pages';
    }

    public function home()
    {
        $bookList = Book::getLatestBook();
        $data = array(
            'bookList' => $bookList
        );


        $this->render('home', $data);
    }

    public function about()
    {
        $this->render('about');
    }

    public function shop()
    {

        //Filter book
        if (isset($_POST['filter_book'])) {
            $bookList = Book::filterBook($_POST['Publisher_ID'], $_POST['Author_ID'], $_POST['Genre_ID']);
            $publisherList = Publisher::getPublisherList();
            $genreList = Genre::getGenreList();
            $authorList = Author::getAuthorList();

            $data = array(
                'bookList' => $bookList,
                'publisherList' => $publisherList,
                'authorList' => $authorList,
                'genreList' => $genreList
            );

            $this->render('shop', $data);
        } //

        // Search book        
        else if (isset($_POST['search_book'])) {
            $bookList = Book::searchBook($_POST['searchInfo']);
            $publisherList = Publisher::getPublisherList();
            $genreList = Genre::getGenreList();
            $authorList = Author::getAuthorList();

            $data = array(
                'bookList' => $bookList,
                'publisherList' => $publisherList,
                'authorList' => $authorList,
                'genreList' => $genreList
            );

            $this->render('shop', $data);
        }

        $bookList = Book::getBookList();
        $publisherList = Publisher::getPublisherList();
        $genreList = Genre::getGenreList();
        $authorList = Author::getAuthorList();

        $data = array(
            'bookList' => $bookList,
            'publisherList' => $publisherList,
            'authorList' => $authorList,
            'genreList' => $genreList,
        );
        $this->render('shop', $data);
    }

    // NOT USED AT THE MOMENT
    public function search()
    {
        $bookList = Book::getBookList();
        $data = array(
            'bookList' => $bookList
        );
        $this->render('search', $data);
    }

    // NOT USED
    public function contact()
    {
        $this->render('contact');
    }

    public function detail()
    {
        if (isset($_GET['ID'])) {

            //if comment
            $message = [];
            if (isset($_POST['comment'])) {
                $Book_ID = $_POST['Book_ID'];
                $Account_ID = $_POST['Account_ID'];
                $Content = $_POST['Content'];
                $Ratings = $_POST['Ratings'];
                $Img = 'User.png';

                $Create_date = date("Y-m-d H:i:s");

                Reviews::insertReview($Create_date, $Account_ID, $Book_ID, $Content, $Ratings, $Img);

                $message[] = "You have just commented !";
            }

            //add to cart
            if (isset($_POST['add_to_cart'])) {
                $Price = $_POST['Price'];
                $Quantity = $_POST['Quantity'];
                $Book_ID = $_POST['Book_ID'];
                $Customer_ID = $_POST['Customer_ID'];

                Cart::addBookToCart($Customer_ID, $Price, $Quantity, $Book_ID);
                $message[] = "Book is added to cart !";
            }
            /////////
            $Book_ID = $_GET['ID'];
            $book = Book::getBook_useBookID($Book_ID);
            $Publisher = Publisher::getPublisher_useBookID($Book_ID);

            //Author handler
            $authorList = Author::getAuthorList_useBookID($Book_ID);
            $author_string = "";
            foreach ($authorList as $author) {
                $author_string = $author_string . $author->Author_name . ", ";
            }
            $authorList = substr($author_string, 0, strlen($author_string) - 2);
            //

            //Genre handler
            $genreList = Genre::getGenreList_useBookID($Book_ID);
            $genre_string = "";
            foreach ($genreList as $genre) {
                $genre_string = $genre_string . $genre->Genre_name . ", ";
            }
            $genreList = substr($genre_string, 0, strlen($genre_string) - 2);
            //

            $reviewList = Reviews::getReviews_useBook_ID($Book_ID);

            $data = array(
                'book' => $book,
                'Publisher' => $Publisher,
                'authorList' => $authorList,
                'genreList' => $genreList,
                'reviewList' => $reviewList,
                'message' => $message
            );
            $this->render('detail', $data);
        }
    }

    public function cart()
    {
        if (isset($_POST['delete_cart_detail'])) {
            $Cart_detail_ID = $_POST['Cart_detail_ID'];

            Cart_detail::deleteCartDetail($Cart_detail_ID);
        }

        if (isset($_POST['update_cart_detail'])) {
            $Cart_detail_ID = $_POST['Cart_detail_ID'];
            $Quantity = $_POST['Quantity'];
            $Price = $_POST['Price'];

            Cart_detail::updateCartDetail($Cart_detail_ID, $Price, $Quantity);
        }

        if (isset($_POST['delete_all'])) {
            $Cart_ID = $_POST['Cart_ID'];
            Cart::emptyCart($Cart_ID);
        }
        $Customer_ID = $_GET['userID'];

        $cartInfo = Cart::getCart($Customer_ID);


        $data = [
            'cartInfo' => $cartInfo
        ];
        $this->render('cart', $data);
    }

    public function checkout()
    {
        $message = [];
        if (isset($_POST['order'])) {

            $Cart_ID = $_POST['Cart_ID'];

            $Customer_ID = $_GET['userID'];
            $Address_M = $_POST['Address_M'];
            $Create_date = date("Y-m-d H:i:s");
            $Status_M = 0;
            $Total_price = Cart::getTotalPrice($Cart_ID);
            $Note = $_POST['Note'];
            $Shipping_ID = $_POST['Method_ID'];
            $Payment_ID = $_POST['Payment_ID'];

            $cartInfo = Cart::getCart($Customer_ID);

            Order::createNewOrder($Customer_ID, $Address_M, $Create_date, $Status_M, $Total_price, $Note, $Shipping_ID, $Payment_ID, $cartInfo);

            $message[] = "Your order has been sent !";
        }
        $Customer_ID = $_GET['userID'];

        $cartInfo = Cart::getCart($Customer_ID);
        $customerInfo = Customer::findAccount_useAccountID($Customer_ID);
        $paymentList = Payment_method::getPaymentList();
        $shippingList = Shipping_method::getShippingList();


        $data = [
            'cartInfo' => $cartInfo,
            'customerInfo' => $customerInfo,
            'paymentList' => $paymentList,
            'shippingList' => $shippingList,
            'message' => $message
        ];

        $this->render('checkout', $data);
    }

    public function orders()
    {

        if (isset($_POST['received'])) {
            $Order_ID = $_POST['Order_ID'];
            Order::updateOrderStatus($Order_ID, 2);
        }

        if (isset($_POST['cancel'])) {
            $Order_ID = $_POST['Order_ID'];
            Order::updateOrderStatus($Order_ID, 3);
        }
        $Customer_ID = $_GET['userID'];
        $orderPlacedList = Order::getOrderPlaced($Customer_ID);
        $Customer = Customer::findAccount_useAccountID($Customer_ID);

        $data = array(
            'orderPlacedList' => $orderPlacedList,
            'Customer' => $Customer
        );
        $this->render('orders', $data);
    }



    public function error()
    {
        $this->render('error');
    }
}
