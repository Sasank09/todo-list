<?php
require_once "../includes/utility.php";
/**
 * Location: To-Do List/Controller/edit_todo.php
 * @file edit_todo.php
 * Display the todo based on Id from the URL paramater by $_GET['id']. Checks with current logged in user_id and todo_id and displays the record
 * Able to edit the record and delete if neeeded. Utilised $.post jQuery method to send the form
 * requires utility.php to run database and common code for the application.
 */
session_start();
$msg = '';
if (isset($_SESSION['user_mail']) && !empty($_SESSION['user_mail']) && isset($_SESSION['login_status']) && $_SESSION['login_status'] === 'SUCCESS') {
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
            if(!$result) {
                $msg = INVALID_ID_NO_TODO_MSG;
            }
        } else {
            $msg = ERROR_404_MSG.' '.INVALID_PARAM_MSG;
        }
    } else {
        $msg = ERROR_404_MSG.' '.INVALID_PARAM_MSG;
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
    <div id="formContainer" class="container m-6">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card bg-light rounded border shadow">
                    <div class="card-header bg-info ">
                        <h4 class="card-title">Edit Todo</h4>
                    </div>
                    <div class="card-body p-4">
                        <div id="update_status"></div>
                        <form action="crud_todos.php" method="POST" id="edit_todo_form">
                            <?php if ($result) {
                                getFormContent($result);

                            ?>
                                <hr>
                                <div id="viewFooter" class="bg-light mt-4 clearfix">
                                    <input type="submit" id="updateTodo" name="updateTodo" class="btn btn-primary float-end" value="Update Todo">
                                    <?php getDeleteButton($result["todo_id"]) ?>
                                </div>
                            <?php
                            } else { ?>
                                <div class="bg-danger m-auto w-75 text-center p-5 fw-bold fs-4"> 
                                    <?php echo htmlentities($msg); ?>
                                </div>
                            <?php
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php getFooter(); ?>
    <script type="text/javascript">
        "use strict";
        //On DOM ready logic
        $(document).ready(function() {
            $("#cover-spin").show().delay(500).fadeOut();
            //set values for dropdown input elements
            $('#status').val("<?php echo $result['status']; ?>");
            $('#priority').val("<?php echo $result['priority'] ?>");
            $('input:radio[name="category"]').filter('[value="<?php echo $result['category']; ?>"]').attr('checked', true);

            //On Clicking update button, using jQuery to send data with post method
            $("#updateTodo").click(function(event) {
                event.preventDefault();
                $("#cover-spin").show();
                const form = $("#edit_todo_form");
                const json = convertFormToJSON(form);
                json["updateTodo"] = "Update Todo";
                json["todoId"] = "<?php echo $result['todo_id'] ?>";
                $.post("crud_todos.php", json, function(response) {
                    var data = JSON.parse(response);
                    if (data.status == "Success") {
                        $("#update_status").html(
                            "<div class='alert alert-success'>" + sanitizeHTML(data.message) + "</div>"
                        ).delay(1000).fadeOut();
                        window.location.replace("/controller/view_todo.php?id=" + json["todoId"]);
                    } else {
                        $("#update_status").html(
                            "<div class='alert alert-danger'>" + sanitizeHTML(data.message) + "</div>"
                        ).delay(1000).fadeOut();
                        $("#cover-spin").delay(500).fadeOut();
                    }
                    $("#edit_todo_form")[0].reset();
                }).fail(function() {
                    $("#cover-spin").delay(700).fadeOut();
                    alert("error");
                });
            });

        });
    </script>
</body>

</html>

<?php
$_POST = array();
die();
?>