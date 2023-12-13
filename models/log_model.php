<?php
//models/log.php


class Log
{
    static function checkIfAccountExist($Email, $TelephoneNum)
    {
        $db = DB::getInstance();
        $sql = "SELECT Account_ID FROM ACCOUNT WHERE Email = :Email OR TelephoneNum = :TelephoneNum";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Email', $Email, PDO::PARAM_STR);
        $stmt->bindParam(':TelephoneNum', $TelephoneNum, PDO::PARAM_STR);

        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($items)) {
            return 1;
        }

        return 0;
    }

    static function addCustomer($Customer_ID, $Bank_ID, $Bank_name)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO CUSTOMER (Customer_ID, Bank_ID, Bank_name) VALUES (:Customer_ID, :Bank_ID, :Bank_name)";

        $stmt = $db->prepare($sql);
        $params = [
            ':Customer_ID' => $Customer_ID,
            ':Bank_ID' => $Bank_ID,
            ':Bank_name' => $Bank_name,
        ];

        $stmt->execute($params);
    }

    static function addNewAccount($FName, $LName, $Email, $TelephoneNum, $H_Password, $Birthday, $Address_M, $Bank_ID, $Bank_name)
    {
        $db = DB::getInstance();

        //INSERT INTO ACCOUNT TABLE
        $sql = "INSERT INTO ACCOUNT (FName, LName, Email, TelephoneNum, H_Password, Birthday, Address_M) VALUES (:FName, :LName, :Email, :TelephoneNum, :H_Password, :Birthday, :Address_M)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':FName', $FName, PDO::PARAM_STR);
        $stmt->bindParam(':LName', $LName, PDO::PARAM_STR);
        $stmt->bindParam(':Email', $Email, PDO::PARAM_STR);
        $stmt->bindParam(':TelephoneNum', $TelephoneNum, PDO::PARAM_STR);
        $stmt->bindParam(':H_Password', $H_Password, PDO::PARAM_STR);
        $stmt->bindParam(':Birthday', $Birthday, PDO::PARAM_STR);
        $stmt->bindParam(':Address_M', $Address_M, PDO::PARAM_STR);

        $stmt->execute();
        //

        //INSERT TO CUSTOMER TABLE
        $getID_sql = "SELECT Account_ID FROM ACCOUNT WHERE Email = :Email";
        $getID_stmt = $db->prepare($getID_sql);
        $getID_stmt->bindParam(':Email', $Email, PDO::PARAM_STR);

        $getID_stmt->execute();

        $ID = $getID_stmt->fetchAll(PDO::FETCH_ASSOC);
        $ID = $ID[0];
        $ID = $ID['Account_ID'];

        Log::addCustomer($ID, $Bank_ID, $Bank_name);
        //
    }

    static function checkAccountType($Account_ID)
    {
        $db = DB::getInstance();

        $sql = "SELECT Customer_ID FROM CUSTOMER WHERE Customer_ID = :Account_ID";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Account_ID', $Account_ID, PDO::PARAM_STR);

        $stmt->execute();

        $customer = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($customer)) {
            return 0; //Account belongs to customer
        }

        return 1; // Account belongs to customer
    }

    static function LoginAuthenticate($Email, $H_Password)
    {
        $db = DB::getInstance();

        $sql = "SELECT * FROM ACCOUNT WHERE Email = :Email AND H_Password = :H_Password";
        $stmt = $db->prepare($sql);
        $params =
            [
                ':Email' => $Email,
                ':H_Password' => $H_Password
            ];

        $stmt->execute($params);

        $exist = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $getInfo = $exist[0];

        if (empty($exist)) {
            return 0; // login fail, incorrect password or email
        }

        $result = array(
            'Account_ID' => $getInfo['Account_ID'],
            'FName' => $getInfo['FName'],
            'LName' => $getInfo['LName'],
            'Email' => $getInfo['Email']
        );

        return $result; //login success
    }
}
