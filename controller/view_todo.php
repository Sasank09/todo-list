<?php
require_once "../includes/utility.php";
/**
 * Location: To-Do List/Controller/view_todo.php
 * @file view_todo.php
 * Display the todo based on Id from the URL paramater by $_GET['id'] s. Checks with current logged in user_id and todo_id and displays the record
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
            if (!$result) {
                $msg = INVALID_ID_NO_TODO_MSG;
            }
        } else {
            $msg = ERROR_404_MSG . ' ' . INVALID_PARAM_MSG;
        }
    } else {
        $msg = ERROR_404_MSG;
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
    <div id="viewContainer" class="container m-6">
        <div class="card bg-light rounded border shadow">
            <div class="card-header bg-warning text-center">
                <h4 class="card-title text-light">View Todo</h4>
            </div>
            <?php
            if ($result) {
            ?>
                <div class="m-2 row">
                    <div class="col-2">
                        <label for="title" class="fs-5 text-seoncdary fw-bold">Title </label>
                    </div>
                    <div class="col-10">
                        <output name="title" class="fs-6"><?php echo htmlentities($result['title']); ?></output>
                    </div>
                </div>
                <div class="m-2 row bg-white">
                    <div class="col-2">
                        <label for="description" class="fs-5 text-seoncdary fw-bold">Description</label>
                    </div>
                    <div class="col-10">
                        <output name="description" class="fs-6"><?php echo htmlentities($result['description']); ?></output>
                    </div>
                </div>
                <div class="m-2 row">
                    <div class="col-2">
                        <label for="priority" class="fs-5 text-seoncdary fw-bold">Priority </label>
                    </div>
                    <div class="col-10">
                        <output name="priority" class="fs-6"><?php echo htmlentities($result['priority']); ?></output>
                    </div>
                </div>
                <div class="m-2 row bg-white">
                    <div class="col-2">
                        <label for="duedate" class="fs-5 text-seoncdary fw-bold">Complete By</label>
                    </div>
                    <div class="col-10">
                        <output name="duedate" class="fs-6"><?php echo date("F jS, Y e h:i A", strtotime(htmlentities($result['due_date']))); ?></output>
                    </div>
                </div>
                <div class="m-2 row">
                    <div class="col-2">
                        <label for="category" class="fs-5 text-seoncdary fw-bold">Category</label>
                    </div>
                    <div class="col-10">
                        <output name="category" class="fs-6"><?php echo htmlentities($result['category']); ?></output>
                    </div>
                </div>
                <div class="m-2 row bg-white">
                    <div class="col-2">
                        <label for="status" class="fs-5 text-seoncdary fw-bold">Status</label>
                    </div>
                    <div class="col-10">
                        <output name="status" class="fs-6"><?php echo htmlentities($result['status']); ?></output>
                    </div>
                </div>
                <div class="m-2 row bg-white">
                    <div class="col-2">
                        <label for="createdon" class="fs-5 text-seoncdary fw-bold">Created Date</label>
                    </div>
                    <div class="col-10">
                        <output name="createdon" class="fs-6"><?php echo htmlentities($result['created_date']); ?></output>
                    </div>
                </div>
                <div class="m-2 row bg-white">
                    <div class="col-2">
                        <label for="createdon" class="fs-5 text-seoncdary fw-bold">Modified Date</label>
                    </div>
                    <div class="col-10">
                        <output name="createdon" class="fs-6"><?php echo htmlentities($result['modified_date']); ?></output>
                    </div>
                </div>
                <div class="card-body p-4">
                    <hr>
                    <div id="viewFooter" class="bg-light mt-3 clearfix">
                        <a href="<?php echo 'edit_todo.php?id=' . htmlentities($result['todo_id']); ?>" class="btn btn-primary float-end px-4 me-2">Edit</a>
                        <?php getDeleteButton($result['todo_id']); ?>
                    </div>
                </div>
            <?php
            } else { ?>
                <div class="bg-danger m-auto w-75 text-center p-5 fw-bold fs-4">
                    <?php echo htmlentities($msg); ?>
                </div>
            <?php
            }
            ?>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#cover-spin").show().delay(500).fadeOut();
        });
    </script>
</body>

</html>
<?php
$_POST = array();
die();
?>