<?php
require_once("includes/config.php");
require_once("includes/classes/ErrorMessages.php");
require_once("includes/classes/Account.php");

$account = new Account($con);

    if(isset($_POST["submitButton"])) {
        $firstName = htmlspecialchars($_POST["firstName"]);
        $firstName = htmlspecialchars($_POST["firstName"]);
        $lastName = htmlspecialchars($_POST["lastName"]);
        $username = htmlspecialchars($_POST["username"]);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $email2 = filter_var($_POST["email2"], FILTER_SANITIZE_EMAIL);
        $password = strip_tags($_POST["password"]);
        $password2 = strip_tags($_POST["password2"]);
        $success = $account->register($firstName, $lastName, $username, $email, $email2, $password, $password2);
        if($success) {
            $_SESSION["userLoggedIn"] = $username;
            header("Location: index.php");
        }

    }

    function getPreviousInput($value) {
        if(isset($_POST[$value])) {
            echo $_POST[$value];
        }
    }  

?>




<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to FitGo</title>
        <link rel="stylesheet" type="text/css" href="res/css/style.css" />
    </head>
    <body>
        
        <div class="signInContainer">

            <div class="column">

                <div class="header">
                    <img src="res/images/logo.png" title="Logo" alt="Site logo" />
                    <h3>Sign Up</h3>
                    <span>to continue to FitGo</span>
                </div>

                <form method="POST">


                    <input type="text" name="firstName" placeholder="First name" value="<?php getPreviousInput("firstName"); ?>"   required>
                    <?php echo $account->getError(ErrorMessages::$firstNameCharacters); ?>

                    <input type="text" name="lastName" placeholder="Last name" value="<?php getPreviousInput("lastName"); ?>"   required>
                    <?php echo $account->getError(ErrorMessages::$lastNameCharacters); ?>

                    <input type="text" name="username" placeholder="Username" value="<?php getPreviousInput("username"); ?>"  required>
                    <?php echo $account->getError(ErrorMessages::$usernameCharacters); ?>
                    <?php echo $account->getError(ErrorMessages::$usernameTaken); ?>

                    <input type="email" name="email" placeholder="Email" value="<?php getPreviousInput("email"); ?>" required>

                    <input type="email" name="email2" placeholder="Confirm email" value="<?php getPreviousInput("email2"); ?>" required>
                    <?php echo $account->getError(ErrorMessages::$emailsDontMatch); ?>
                    <?php echo $account->getError(ErrorMessages::$emailInvalid); ?>
                    <?php echo $account->getError(ErrorMessages::$emailTaken); ?>

                    <input type="password" name="password" placeholder="Password" required>

                    <input type="password" name="password2" placeholder="Confirm password" required>
                    <?php echo $account->getError(ErrorMessages::$passwordsDontMatch); ?>
                    <?php echo $account->getError(ErrorMessages::$passwordLength); ?>
                    <input type="submit" name="submitButton" value="SUBMIT">

                </form>

                <a href="login.php" class="signInMessage">Already have an account? Sign in here!</a>

            </div>

        </div>

    </body>
</html>