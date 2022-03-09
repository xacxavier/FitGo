<?php
    require_once("includes/config.php");
    require_once("includes/classes/ErrorMessages.php");
    require_once("includes/classes/Account.php");

    $account = new Account($con);

    if(isset($_POST["submitButton"])) {
            $username = htmlspecialchars($_POST["username"]);
            $password = strip_tags($_POST["password"]);
            $success = $account->login($username, $password);
            if($success) {
                $_SESSION["userLoggedIn"] = $username;
                header("Location: index.php");
            }

        }


    function getPreviousInput($name) {
        if(isset($_POST[$name])) {
            echo $_POST[$name];
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
                    <h3>Sign In</h3>
                    <span>to continue to FitGo</span>
                </div>

                <form method="POST">
                
                    <input type="text" name="username" placeholder="Username" value="<?php getPreviousInput("username"); ?>" required>

                    <input type="password" name="password" placeholder="Password" required>
                    <?php echo $account->getError(ErrorMessages::$loginFailed); ?>
                    <input type="submit" name="submitButton" value="Login">
                    
                </form>

                <a href="register.php" class="signInMessage">Need an account? Sign up here!</a>

            </div>

        </div>

    </body>
</html>