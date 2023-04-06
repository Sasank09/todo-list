<?php
$lifetime=7200;
session_set_cookie_params($lifetime);
require_once "includes/utility.php";
/**
 * @file index.php
 * Landing page for users to login/register and about basic info.
 * requires utility.php to run database and common code for the application.
 */
//Setting session_max lifespan for 2 hours
session_start();
$loginErrorMessage = "";
// checking login session
if (isset($_SESSION['login_status']) && $_SESSION['login_status'] === 'FAIL') {
    $loginErrorMessage = INVALID_USER_CREDS_MSG;
    $mail = isset($_COOKIE['mail']) ? $_COOKIE['mail'] :'';
    setcookie('mail','', time()-1);
    unset($_SESSION['login_status']);
} elseif (isset($_SESSION['user_mail'])  && isset($_SESSION['login_status']) &&  !empty($_SESSION['user_mail']) && $_SESSION['login_status'] === 'SUCCESS') {
    $loginErrorMessage = "";
    header("refresh:0; url=" . ALL_TODO_LIST_PHP_LOCATION);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List Home</title>

    <!-- Style Reference and Libraries -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/index.css">

    <!--JS Reference and jQuery Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js" integrity="sha512-6S5LYNn3ZJCIm0f9L6BCerqFlQ4f5MwNKq+EthDXabtaJvg3TuFLhpno9pcm+5Ynm6jdA9xfpQoMz2fcjVMk9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="js/todo_list.js"></script>
    <script>
        window.addEventListener('load', onLoadingIndexPage);
    </script>
</head>

<body">
    <div id="index_body" class="index_main_body">
        <div id="cover-spin"></div>
        <!-- Navbar (sit on top) -->
        <div class="w3-top " style="z-index: 1000;">
            <div class="w3-row w3-padding w3-white w3-left-align" style="letter-spacing:2px;">
                <div class="w3-col s3 w3-mobile">
                    <a href="#about" class="w3-button w3-block w3-white nav_but" onblur="window.location.reload()">To-List
                        Home</a>
                </div>
                <div class="w3-col s3 w3-hide-small">
                    <a href="#about" class="w3-button w3-block w3-white nav_but" onblur="window.location.reload()">About</a>
                </div>
                <div class="w3-col s3 w3-mobile">
                    <a href="#loginForm" class="w3-button w3-block w3-white switch-btn w3-mobile">Login/Register</a>
                </div>
                <div class="w3-col s3 w3-hide-small">
                    <a href="#team" class="w3-button w3-block w3-white nav_but">Team</a>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="w3-content" style="max-width: 100%">
            <!-- About Section -->
            <div class="w3-row w3-padding-64 w3-padding-large" id="about">
                <div class="w3-col m6 w3-padding-large w3-hide-small">
                    <img src="images/todo.jpg" class="w3-round w3-image w3-opacity-min fade-in" alt="To Do" width="100%" height="auto" />
                </div>
                <div class="w3-col m6 w3-padding-large">
                    <h1 class="w3-right w3-text-black typewriter w3-hover-text-brown w3-hide-small w3-hide-medium" style="text-shadow: 2px 2px 5px red">
                        About To-Do List.
                    </h1>
                    <h1 class="w3-right w3-text-black typewriter w3-hover-text-brown w3-hide-large" style="text-shadow: 2px 2px 5px red">
                        About.
                    </h1>
                    <br />
                    <hr />
                    <p class="w3-large w3-justify">
                        In our daily busy life, one always has many
                        things/tasks/short-term goals to accomplish on a day. And
                        sometimes people forget to do things that are important and get
                        frustrates not being able to complete them as the day goes
                        forgetting them. In Business area, other people will have to chase
                        you to get things done while struggling to keep up to deadlines
                        because of heavy workload. Traditionally, we write in notes/ diary
                        to keep track of them, but as the technology advanced, we are
                        going to utilize the web application. To-Do List web app is for
                        general daily usage and the main aim of this application is to
                        create goals/ tasks so that user can track and complete them by
                        specified point of time. The end users can manage their day-to-day
                        tasks or things that must be completed in a day in much more
                        organized way by prioritizing them. With to-do list, users will
                        get clear outline of tasks to be completed or important for the
                        day and able to work on the tasks that are important and leave
                        less prioritized one to little later if time is not sufficient.
                        Users can mark the tasks completed and able to move onto next one
                        orderly. With the help of this app, user will be more effective by
                        completing all the important things and tracking the next goals/
                        tasks on a day.
                    </p>
                </div>
            </div>
            <hr />
        </div>

        <!-- Login/Registration Section -->
        <div class="w3-content w3-row w3-padding-64 w3-center w3-mobile" id="loginForm">
            <div class="main">
                <div class="container a-container" id="a-container">
                    <form class="form" id="login-form" name="login-form" method="POST" action="controller/login.php">
                        <h2 class="form_title title">Login</h2>
                        <div id="login-status" class="w3-center w3-padding-8 error"><?php echo htmlentities($loginErrorMessage) ?></div>
                        <div>
                            <i class="fas fa-envelope"></i>
                            <input class="form__input" type="email" placeholder="Email" id="email" name="email" value="<?php echo htmlentities($mail) ?>" required>
                        </div>
                        <div>
                            <i class="fas fa-eye-slash" id="log_pass" onclick="togglePasswordVisibility(id,'passwd')" style="cursor: pointer;"></i>
                            <input class="form__input" type="password" placeholder="Password" id="passwd" name="passwd" required>
                        </div>
                        <input type="submit" value="Login" id="login" name="login" class="form__button button">
                    </form>
                </div>
                <div class="container b-container" id="b-container">
                    <form class="form" id="registration-form" name="registration-form" method="POST" action="controller/register.php">
                        <h2 class="form_title title">Create Account</h2>
                        <div id="user-availability-status" class="w3-center w3-padding-8"></div>
                        <div>
                            <i class="fas fa-user"></i>
                            <input class="form__input" type="text" placeholder="Name" id="fullname" name="fullname" minlength="3" required>
                        </div>
                        <div>
                            <i class="fas fa-envelope"></i>
                            <input class="form__input" type="email" placeholder="Email" id="mail" name="mail" required>
                        </div>
                        <div>
                            <i class="fas fa-eye-slash" id="reg_pass" onclick="togglePasswordVisibility(id,'pass')" style="cursor: pointer;"></i>
                            <input class="form__input" type="password" placeholder="Password" id="pass" name="pass" required>
                        </div>
                        <div>
                            <i class="fas fa-eye-slash" id="reg_rpass" onclick="togglePasswordVisibility(id,'retype_pass')" style="cursor: pointer;"></i>
                            <input class="form__input" type="password" placeholder="Retype Password" id="retype_pass" name="retype_pass" required>
                        </div>
                        <input type="submit" value="Register" id="register" name="register" class="form__button button">
                    </form>
                </div>
                <!-- Login / Registration Forms switch  containes to toggle -->
                <div class="switch" id="switch-cnt">
                    <div class="switch__circle"></div>
                    <div class="switch__circle switch__circle--t"></div>
                    <div class="switch__container" id="switch-c1">
                        <h2 class="switch__title title">Join Us</h2>
                        <p class="switch__description description w3-justify">Don't Have an account?.. Click below to enter your
                            personal details and start journey with us to manage your todo's more effectively
                        </p>
                        <button class="switch__button button switch-btn">To Register</button>
                    </div>
                    <div class="switch__container is-hidden" id="switch-c2">
                        <h2 class="switch__title title">Welcome!</h2>
                        <p class="switch__description description w3-justify">Already have an account?.. Click below to keep
                            connected with us with your personal info & manage your todo's</p>
                        <button class="switch__button button switch-btn">To Login</button>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <!--Team Section-->
        <div class="w3-content" style="max-width:100%">
            <div class="w3-row w3-padding-64" style="  margin: auto;width: 50%;" id="team">
                <div class="w3-col w3-padding-large">
                    <h3 class="w3-center">Development Team</h3><br>
                    <h4 class="w3-center">Advanced Web Applications & Services Development</h4>
                    <hr>
 
                    <h4>Professor: Dr. Sung</h4>
                    <p class="w3-text-dark-grey">CS5130 - CRN 13892</p><br>

                    <h4>1. Venkata Lakshmi Sasank Tipparaju</h4>
                    <p class="w3-text-dark-grey">Student ID# 700738838</p><br>
                </div>
            </div>
            <hr><br>
        </div>
        <hr>
        <!-- Footer -->
        <footer class="w3-bottom w3-center w3-black w3-padding-small" style="z-index: 1000;">
            <p>Developed by <a href="" class="w3-hover-text-green">Sasank Tipparaju</a></p>
            <p>GitHub: <a href="https://github.com/Sasank09/todo-list">Link</a></p>
            <p>NOTE: Please Don't add any Sensitive Information - This is just a sample application</p>
        </footer>
    </div>
    </body>

</html>