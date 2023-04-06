<?php

/**
 * Location: To-Do List/Controller/register.php
 * @file register.php
 * Registers the users and if successful navigate to the application elsewhere back to index page
 * requires utility.php to run database and common code for the application.
 */
require_once "../includes/utility.php";
session_start();
$msg = REGISTRATION_PAGE_MSG;
if (isset($_SESSION['user_mail']) && isset($_SESSION['login_status']) && $_SESSION['user_mail'] != '' && $_SESSION['login_status'] == 'SUCCESS') {
    header("refresh:2; url=" . ALL_TODO_LIST_PHP_LOCATION);
} elseif (isset($_POST['register']) && isset($_POST['mail']) && isset($_POST['pass']) && isset($_POST['fullname'])) {
    try {
        //Sanitize the inputs read from the registration form filled by user
        $fullname = htmlentities($_POST['fullname']);
        $pass = htmlentities($_POST['pass']);
        //php server side validation
        $mail = filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL); //returns the email address if valid, else returns empty string/false 
        $isfullnameValid = strlen($fullname) >= 3 ? true : false; //checks if fullname has length more than 3 chars
        $password_regex = "/^(?=.*?[a-zA-Z])(?=.*?[0-9])(?=.*?[_#?!@$%^&*-]).{8,15}$/";
        $isPassWordValid  = preg_match($password_regex, $pass); // returns 0,1 if matches the regular expression
        //check if inputes are null, and then check if user already registered, else create a new user record
        if ($fullname && $mail && $pass && $isPassWordValid && $isfullnameValid) {
            $sql = "SELECT fullname, email, password FROM users WHERE email = :mailId";
            $bindParams = array(
                "mailId" => $mail
            );
            if (!executeQuery($sql, $bindParams, "ONE")) {
                $insertQuery  = "INSERT INTO users (fullname, email, password) VALUES (:name, :mail, :pass)";
                //hash("PASSWORD_BCRYPT", $pass) hashing the password to hide actual value in database
                $hashed_pwd = password_hash($pass, PASSWORD_BCRYPT);
                $insertBindParams = array(
                    "name" => $fullname,
                    "mail" => $mail,
                    "pass" => $hashed_pwd
                );
                if (executeQuery($insertQuery, $insertBindParams, "NONE")) {
                    $_SESSION['user_mail'] = $mail;
                    $_SESSION['user_fullname'] = $fullname;
                    $_SESSION['login_status'] = "SUCCESS";
                    setcookie('login_time', date(" Y-m-d H:i:s", time()) ,time()+86400,'/');
                    //clearing post variable by reinstialising after successful creation
                    $_POST = array();
                    header("refresh:2; url=" . ALL_TODO_LIST_PHP_LOCATION);
                } else {
                    $msg = REGISTRATION_FAIL_REDIRECT_MSG;
                    header("refresh:2; url=" . INDEX_LOGIN_PAGE_LOCATION);
                }
            } else {
                $msg = EMAIL_ALREADY_EXISTS_MSG . REGISTRATION_FAIL_REDIRECT_MSG;
                header("refresh:2; url=" . INDEX_LOGIN_PAGE_LOCATION);
            }
        } else {
            $msg = INVALID_USER_CREDS_MSG . REGISTRATION_FAIL_REDIRECT_MSG;
            header("refresh:2; url=" . INDEX_LOGIN_PAGE_LOCATION);
        }
    } catch (Exception $e) {
        $msg = REGISTRATION_FAIL_REDIRECT_MSG . '\n Something went wrong, Exception Thrown: ' . $e->getMessage();
        header("refresh:2; url=" . INDEX_PAGE_LOCATION);
    }
} else {
    $msg = NEED_TO_LOGIN_MSG;
    header("refresh:0; url=" . INDEX_PAGE_LOCATION);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php getHead(); ?>
</head>

<body>
    <?php getHeader(); ?>
    <div class="bg-warning m-auto w-75 text-center p-3 fw-bold fs-4">
        <?php echo htmlentities($msg); ?>
    </div>
    <?php getFooter(); ?>
    <script>
        $(document).ready(function() {
            $("#cover-spin").show().delay(2000).fadeOut();
        });
    </script>
</body>

</html>
<?php
$_POST = array();
die();
?>