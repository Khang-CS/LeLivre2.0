<?php
require_once('controllers/base_controller.php');
require_once('models/log_model.php');

class LogController extends BaseController
{
    function __construct()
    {
        $this->folder = 'logPages';
    }

    public function login()
    {
        if (isset($_POST['login'])) {
            $Email = $_POST['Email'];
            $H_Password = md5($_POST['H_Password']);
            $message = "login successfully";

            $authenticate = Log::LoginAuthenticate($Email, $H_Password);
            if (!$authenticate) {
                $message = "Incorrect Email or Password !";
            } else {
                $accountType = Log::checkAccountType($authenticate['Account_ID']);
                session_start();
                switch ($accountType) {
                    case 0: // CUSTOMER
                        $_SESSION['user_name'] = $authenticate['LName'] . " " . $authenticate['FName'];
                        $_SESSION['user_email'] = $authenticate['Email'];
                        $_SESSION['user_id'] = $authenticate['Account_ID'];
                        header('location:index.php?controller=pages&action=home');
                        break;
                    default: //STAFF
                        $_SESSION['user_name'] = $authenticate['LName'] . " " . $authenticate['FName'];
                        $_SESSION['user_email'] = $authenticate['Email'];
                        $_SESSION['user_id'] = $authenticate['Account_ID'];
                        header('location:index.php?controller=adminPages&action=home');
                        break;
                }
            }

            $data = array('message' => $message);
            $this->render('login', $data);
        }


        $this->render('login');
    }

    public function logout()
    {
        $this->render('logout');
    }

    public function register()
    {
        if (isset($_POST['register'])) {
            $message = "Login successfully";

            //GET INPUT DATA
            $FName = $_POST['FName'];
            $LName = $_POST['LName'];
            $Email = $_POST['Email'];
            $TelephoneNum = $_POST['TelephoneNum'];
            $Birthday = $_POST['Birthday'];
            $Address_M = $_POST['Address_M'];
            $password = md5($_POST['password']);
            $cpassword = md5($_POST['cpassword']);
            $Bank_ID = $_POST['Bank_ID'];
            $Bank_name = $_POST['Bank_name'];

            $checkExist = Log::checkIfAccountExist($Email, $TelephoneNum);

            //check if the account is exist
            if ($checkExist) {
                $message = "Email or Telephone number has been used !";
            }
            // 
            else {
                if ($password !== $cpassword) {
                    // check if password confirmation is match
                    $message = "Confirm password does not match !";
                } else {
                    Log::addNewAccount($FName, $LName, $Email, $TelephoneNum, $password, $Birthday, $Address_M, $Bank_ID, $Bank_name);
                }
            }
            // header('location:login.php');

            $data = array("message" => $message);
            $this->render('register', $data);
        }



        $this->render('register');
    }

    public function error()
    {
        $this->render('error');
    }
}
