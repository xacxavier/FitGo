<?php
class Account {

    public $con;
    public $errorArray = array();

    public function __construct($con) {
        $this->con = $con;
    }

    //register function validates the form
    public function register($fn, $ln, $un, $em, $em2, $pw, $pw2) {
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateUsername($un);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        //if the input is valid
        if(empty($this->errorArray)) { 
            //insert details
            return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
        }
        return false;
    }
    


    // insert user details
    private function insertUserDetails($fn, $ln, $un, $em, $pw) {
        
        //hash password
        $pw = hash("sha256", $pw);
        
        $query = $this->con->prepare("INSERT INTO users (firstName, lastName, username, email, password)
                                        VALUES (:fn, :ln, :un, :em, :pw)");
        $query->bindValue(":fn", $fn);
        $query->bindValue(":ln", $ln);
        $query->bindValue(":un", $un);
        $query->bindValue(":em", $em);
        $query->bindValue(":pw", $pw);

        return $query->execute();
    }


    //login page validation
    
    public function login($un, $pw) {
        $pw = hash("sha256", $pw);

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
        $query->bindValue(":un", $un);
        $query->bindValue(":pw", $pw);

        $query->execute();

        if($query->rowCount() == 1) {
            return true;
        }

        array_push($this->errorArray, ErrorMessages::$loginFailed);
        return false;
    }


    //print the error message in the form
    public function getError($error) {
        if(in_array($error, $this->errorArray)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }


    //===================================================================================================================
                                  // Validating functions used by register function above
    //===================================================================================================================

    public function validateFirstName($firstName) {
        if(strlen($firstName) < 2 || strlen($firstName) > 25) {
            array_push($this->errorArray, ErrorMessages::$firstNameCharacters);
        }
    }

    public function validateLastName($lastName) {
        if(strlen($lastName) < 2 || strlen($lastName) > 25) {
            array_push($this->errorArray, ErrorMessages::$lastNameCharacters);
        }
    }

    public function validateUsername($username) {
        if(strlen($username) < 2 || strlen($username) > 25) {
            array_push($this->errorArray, ErrorMessages::$usernameCharacters);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un");
        $query->bindValue(":un", $username);

        $query->execute();
        
        if($query->rowCount() != 0) {
            array_push($this->errorArray, ErrorMessages::$usernameTaken);
        }
    }

    public function validateEmails($em, $em2) {
        if($em != $em2) {
            array_push($this->errorArray, ErrorMessages::$emailsDontMatch);
            return;
        }

        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, ErrorMessages::$emailInvalid);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE email=:em");
        $query->bindValue(":em", $em);

        $query->execute();
        
        if($query->rowCount() != 0) {
            array_push($this->errorArray, ErrorMessages::$emailTaken);
        }
    }

    public function validatePasswords($pw, $pw2) {
        if($pw != $pw2) {
            array_push($this->errorArray, ErrorMessages::$passwordsDontMatch);
            return;
        }

        if(strlen($pw) < 5 || strlen($pw) > 25) {
            array_push($this->errorArray, ErrorMessages::$passwordLength);
        }
    }
}
?>