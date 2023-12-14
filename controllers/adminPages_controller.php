<?php
require_once('controllers/base_controller.php');
require_once('models/admin.php');

class AdminPagesController extends BaseController
{
    function __construct()
    {
        $this->folder = 'adminPages';
    }

    public function home()
    {
        $this->render('home');
    }

    public function manageAuthor()
    {
        if (isset($_POST['add_author'])) {
            Author::addAuthor($_POST['Author_name']);
        }

        if (isset($_POST['update_author'])) {
            Author::updateAuthor($_POST['Author_ID'], $_POST['Author_name']);
        }

        if (isset($_POST['delete_author'])) {
            Author::deleteAuthor($_POST['Author_ID']);
        }

        $authorList = Author::getAuthorList();

        $data = array('authorList' => $authorList);

        $this->render('manageAuthor', $data);
    }

    public function manageGenre()
    {
        if (isset($_POST['add_genre'])) {
            Genre::addGenre($_POST['Genre_name']);
        }

        if (isset($_POST['update_genre'])) {
            Genre::updateGenre($_POST['Genre_ID'], $_POST['Genre_name']);
        }

        if (isset($_POST['delete_genre'])) {
            Genre::deleteGenre($_POST['Genre_ID']);
        }



        $genreList = Genre::getGenreList();

        $data = array('genreList' => $genreList);

        $this->render('manageGenre', $data);
    }

    public function managePublisher()
    {
        if (isset($_POST['add_publisher'])) {
            Publisher::addPublisher($_POST['Publisher_name']);
        }

        if (isset($_POST['update_publisher'])) {
            Publisher::updatePublisher($_POST['Publisher_ID'], $_POST['Publisher_name']);
        }

        if (isset($_POST['delete_publisher'])) {
            Publisher::deletePublisher($_POST['Publisher_ID']);
        }



        $publisherList = Publisher::getPublisherList();

        $data = array('publisherList' => $publisherList);

        $this->render('managePublisher', $data);
    }

    public function manageBook()
    {
        $message = [];
        if (isset($_POST['add_book'])) {

            $Book_name = $_POST['Book_name'];
            $O_Price = $_POST['O_Price'];
            $Discount = $_POST['Discount'];
            $Publish_year = $_POST['Publish_year'];
            $Quantity = $_POST['Quantity'];

            $Publisher_ID = $_POST['Publisher_ID'];
            $Description = $_POST['Description'];

            //Multiple value field  - Genre and Author
            $Genre_string = $_POST['Genre_name'];
            $Author_string = $_POST['Author_name'];
            //

            //Image file
            $image = $_FILES['image']['name'];
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = './assets/book_image/' . $image;
            //


            // If image file is too large
            if ($image_size > 2000000) {
                $message[] = 'image size is too large';
            } //

            else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $Book_ID = Book::addBook($Book_name, $O_Price, $Discount, $Publish_year, $image, $Quantity, $Description);

                Publisher::insertPublish($Publisher_ID, $Book_ID);


                //AUTHOR HANDLER
                $position = 0;
                while (true) {
                    $position = strpos($Author_string, ',');
                    if ($position !== false) {

                        $AuthorGet = substr($Author_string, 0, $position); //Author_name
                        $Author_string = substr($Author_string, $position + 2);

                        $Author_ID = Author::getAuthorID_useName($AuthorGet);
                        if (!$Author_ID) {
                            Author::addAuthor($AuthorGet);
                            $Author_ID = Author::getAuthorID_useName($AuthorGet);

                            $message[] = $AuthorGet . " was not exist and is added to Author list";
                        }

                        Author::insertWrite($Author_ID, $Book_ID);
                    } else {
                        $AuthorGet = $Author_string;
                        $Author_ID = Author::getAuthorID_useName($AuthorGet);
                        if (!$Author_ID) {
                            Author::addAuthor($AuthorGet);
                            $Author_ID = Author::getAuthorID_useName($AuthorGet);

                            $message[] = $AuthorGet . " was not exist and is added to Author list";
                        }

                        Author::insertWrite($Author_ID, $Book_ID);
                        break;
                    }
                }
                //

                //GENRE HANDLER
                $position = 0;
                while (true) {
                    $position = strpos($Genre_string, ',');
                    if ($position !== false) {

                        $GenreGet = substr($Genre_string, 0, $position); //Genre_name
                        $Genre_string = substr($Genre_string, $position + 2);

                        $Genre_ID = Genre::getGenreID_useName($GenreGet);
                        if (!$Genre_ID) {
                            Genre::addGenre($GenreGet);
                            $Genre_ID = Genre::getGenreID_useName($GenreGet);

                            $message[] = $GenreGet . " was not exist and is added to Genre list";
                        }

                        Genre::insertBelongsTo($Genre_ID, $Book_ID);
                    } else {
                        $GenreGet = $Genre_string;
                        $Genre_ID = Genre::getGenreID_useName($GenreGet);
                        if (!$Genre_ID) {
                            Genre::addGenre($GenreGet);
                            $Genre_ID = Genre::getGenreID_useName($GenreGet);

                            $message[] = $GenreGet . " was not exist and is added to Genre list";
                        }

                        Genre::insertBelongsTo($Genre_ID, $Book_ID);
                        break;
                    }
                }


                $message[] = $Book_name . " - ID: " . $Book_ID . ' is added successfully';
            }
        }
        // delete book
        else if (isset($_POST['delete_book'])) {
            $Book_ID = $_POST['Book_ID'];
            $Book_name = $_POST['Book_name'];
            $Thumbnail = $_POST['Thumbnail'];

            $message[] = $Book_name . " - ID: " . $Book_ID . " is permanently deleted !";

            Publisher::deletePublish($Book_ID);
            Author::deleteWrite($Book_ID);
            Genre::deleteBelongsTo($Book_ID);
            Book::deleteBook($Book_ID);
            unlink('./assets/book_image/' . $Thumbnail);
        }

        // search book
        else if (isset($_POST['search_book'])) {
            $bookList = Book::searchBook($_POST['searchInfo']);
            $publisherList = Publisher::getPublisherList();
            $genreList = Genre::getGenreList();
            $authorList = Author::getAuthorList();

            $data = array(
                'bookList' => $bookList,
                'publisherList' => $publisherList,
                'authorList' => $authorList,
                'genreList' => $genreList,
                'message' => $message
            );

            $this->render('manageBook', $data);
        }

        //Filter book
        else if (isset($_POST['filter_book'])) {
            $bookList = Book::filterBook($_POST['Publisher_ID'], $_POST['Author_ID'], $_POST['Genre_ID']);
            $publisherList = Publisher::getPublisherList();
            $genreList = Genre::getGenreList();
            $authorList = Author::getAuthorList();

            $data = array(
                'bookList' => $bookList,
                'publisherList' => $publisherList,
                'authorList' => $authorList,
                'genreList' => $genreList,
                'message' => $message
            );

            $this->render('manageBook', $data);
        }
        //

        $bookList = Book::getBookList();
        $publisherList = Publisher::getPublisherList();
        $genreList = Genre::getGenreList();
        $authorList = Author::getAuthorList();

        $data = array(
            'bookList' => $bookList,
            'publisherList' => $publisherList,
            'authorList' => $authorList,
            'genreList' => $genreList,
            'message' => $message
        );

        $this->render('manageBook', $data);
    }

    public function manageBookDetail()
    {

        if (isset($_GET['update'])) {
            if (isset($_POST['update_book'])) {
                $Book_ID = $_POST['Book_ID'];
                $Book_name = $_POST['Book_name'];
                $O_Price = $_POST['O_Price'];
                $Discount = $_POST['Discount'];
                $Publish_year = $_POST['Publish_year'];
                $Quantity = $_POST['Quantity'];

                $old_image = $_POST['update_old_image'];
                $image = $old_image;

                $Publisher_ID = $_POST['Publisher_ID'];
                $Description = $_POST['Description'];

                //Multiple value field  - Genre and Author
                $Genre_string = $_POST['Genre_name'];
                $Author_string = $_POST['Author_name'];
                //

                //Image file

                $image_size = 2;

                if (strlen($_FILES['image']['name']) > 1) {

                    $image = $_FILES['image']['name'];
                    $image_size = $_FILES['image']['size'];
                }


                // If image file is too large
                if ($image_size > 2000000) {
                    $message[] = 'image size is too large';
                } //

                else {
                    if (strlen($_FILES['image']['name']) > 1) {

                        $image_tmp_name = $_FILES['image']['tmp_name'];
                        $image_folder = './assets/book_image/' . $image;
                        unlink('./assets/book_image/' . $old_image);
                        move_uploaded_file($image_tmp_name, $image_folder);
                    }

                    Book::updateBook($Book_ID, $Book_name, $O_Price, $Discount, $Publish_year, $image, $Quantity, $Description);
                    Publisher::deletePublish($Book_ID);
                    Publisher::insertPublish($Publisher_ID, $Book_ID);


                    //AUTHOR HANDLER
                    Author::deleteWrite($Book_ID);
                    $position = 0;
                    while (true) {
                        $position = strpos($Author_string, ',');
                        if ($position !== false) {

                            $AuthorGet = substr($Author_string, 0, $position); //Author_name
                            $Author_string = substr($Author_string, $position + 2);

                            $Author_ID = Author::getAuthorID_useName($AuthorGet);
                            if (!$Author_ID) {
                                Author::addAuthor($AuthorGet);
                                $Author_ID = Author::getAuthorID_useName($AuthorGet);

                                $message[] = $AuthorGet . " was not exist and is added to Author list";
                            }

                            Author::insertWrite($Author_ID, $Book_ID);
                        } else {
                            $AuthorGet = $Author_string;
                            $Author_ID = Author::getAuthorID_useName($AuthorGet);
                            if (!$Author_ID) {
                                Author::addAuthor($AuthorGet);
                                $Author_ID = Author::getAuthorID_useName($AuthorGet);

                                $message[] = $AuthorGet . " was not exist and is added to Author list";
                            }

                            Author::insertWrite($Author_ID, $Book_ID);
                            break;
                        }
                    }
                    //

                    //GENRE HANDLER
                    Genre::deleteBelongsTo($Book_ID);
                    $position = 0;
                    while (true) {
                        $position = strpos($Genre_string, ',');
                        if ($position !== false) {

                            $GenreGet = substr($Genre_string, 0, $position); //Genre_name
                            $Genre_string = substr($Genre_string, $position + 2);

                            $Genre_ID = Genre::getGenreID_useName($GenreGet);
                            if (!$Genre_ID) {
                                Genre::addGenre($GenreGet);
                                $Genre_ID = Genre::getGenreID_useName($GenreGet);

                                $message[] = $GenreGet . " was not exist and is added to Genre list";
                            }

                            Genre::insertBelongsTo($Genre_ID, $Book_ID);
                        } else {
                            $GenreGet = $Genre_string;
                            $Genre_ID = Genre::getGenreID_useName($GenreGet);
                            if (!$Genre_ID) {
                                Genre::addGenre($GenreGet);
                                $Genre_ID = Genre::getGenreID_useName($GenreGet);

                                $message[] = $GenreGet . " was not exist and is added to Genre list";
                            }

                            Genre::insertBelongsTo($Genre_ID, $Book_ID);
                            break;
                        }
                    }



                    $message[] = $Book_name . " - ID: " . $Book_ID . ' is added successfully';
                }
            }

            $Book_ID = $_GET['update'];
            $book = Book::getBook_useBookID($Book_ID);

            $publisherList = Publisher::getPublisherList();
            $chosenPublisher = Publisher::getPublisher_useBookID($Book_ID);

            //Author handler
            $chosenAuthorList = Author::getAuthorList_useBookID($Book_ID);
            if (!empty($chosenAuthorList)) {
                $Author_string = "";

                foreach ($chosenAuthorList as $author) {
                    $Author_string = $Author_string . $author->Author_name . ", ";
                }

                $Author_string = substr($Author_string, 0, strlen($Author_string) - 2);
                $chosenAuthorList = $Author_string;
            }
            //

            //Genre handler
            $chosenGenreList = Genre::getGenreList_useBookID($Book_ID);
            if (!empty($chosenGenreList)) {
                $Genre_string = "";

                foreach ($chosenGenreList as $genre) {
                    $Genre_string = $Genre_string . $genre->Genre_name . ", ";
                }

                $Genre_string = substr($Genre_string, 0, strlen($Genre_string) - 2);
                $chosenGenreList = $Genre_string;
            }

            $data = array(
                'book' => $book,
                'publisherList' => $publisherList,
                'chosenPublisher' => $chosenPublisher,
                'chosenAuthorList' => $chosenAuthorList,
                'chosenGenreList' => $chosenGenreList
            );


            $this->render('manageBookDetail', $data);
        }




        $this->render('manageBookDetail');
    }

    public function error()
    {
        $this->render('error');
    }
}
