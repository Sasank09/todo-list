<?php
require_once "../includes/utility.php";
/**
 * Location: To-Do List/Controller/delete_todo.php
 * @file delete_todo.php
 * deletes the specified todo based on id
 * requires utility.php to run database and common code for the application.
 */
session_start();
$msg = '';
if (isset($_SESSION['user_mail']) && isset($_SESSION['login_status']) && !empty($_SESSION['user_mail']) && $_SESSION['login_status'] === 'SUCCESS') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $todoId =  htmlentities($_GET["id"]);
        $userDetails = getUserDetails($_SESSION['user_mail']);
        if ($todoId && $userDetails) {
            $sql = "SELECT * FROM todos WHERE todo_id= :id AND user_id= :uid";
            $param = array(
                "id" => $todoId,
                "uid" => $userDetails['user_id'],
            );
            $result = executeQuery($sql, $param, "ONE");
            if($result) {
                $dsql = "DELETE FROM todos WHERE user_id= :user_id AND todo_id= :id";
                $params = array('user_id' => $userDetails['user_id'], 'id' => $todoId );
                if(executeQuery($dsql, $params, "NONE")) {
                    $msg = DELETE_TODO_SUCCESS_MSG;
                    header("refresh:2;url=" . ALL_TODO_LIST_PHP_LOCATION);
                }else {
                    $msg = DELETE_TODO_FAIL_MSG; 
                    header("refresh:2;url=" . ALL_TODO_LIST_PHP_LOCATION);
                }
            }else {
                $msg = DELETE_TODO_FAIL_MSG; 
                header("refresh:2;url=" . ALL_TODO_LIST_PHP_LOCATION);
            }

        } else {
            $msg = ERROR_404_MSG.' '.INVALID_PARAM_MSG;
            header("refresh:2;url=" . ALL_TODO_LIST_PHP_LOCATION);
        }
    } else {
        $msg = ERROR_404_MSG.' '.INVALID_PARAM_MSG;
        header("refresh:2;url=" . ALL_TODO_LIST_PHP_LOCATION);
    }
} else {
    $msg = NEED_TO_LOGIN_MSG;
    header("refresh:1;url=" . INDEX_PAGE_LOCATION);
}
?>
<!doctype html>
<html lang="en">

<head>
    <?php getHead(); ?>
</head>

<body>
    <?php getHeader(); ?>
    <div id="deleteContainer" class="container m-6">
        <div class="bg-info m-auto w-75 text-center p-5 fw-bold fs-4">
            <?php echo htmlentities($msg); ?>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#cover-spin").show().delay(700).fadeOut();
        });
    </script>
</body>

</html>
<?php
$_POST = array();
die();
?>