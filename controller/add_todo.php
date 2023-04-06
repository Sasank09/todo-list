<?php
include "../includes/utility.php";
/**
 * Location: To-Do List/Controller/add_todo.php
 * @file add_todo.php
 * Display the todo form to the user, to get inputs
 * requires utility.php to run database and common code for the application.
 */
session_start();
if (!(isset($_SESSION['user_mail']) && !empty($_SESSION['user_mail']) && isset($_SESSION['login_status']) && $_SESSION['login_status'] === 'SUCCESS')) {
    header("refresh:1;url=" . INDEX_PAGE_LOCATION);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php getHead(); ?>
</head>

<body>
    <?php getHeader(); ?>
    <div id="addContainer" class="container m-6">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card bg-light rounded border shadow">
                    <div class="card-header bg-info input-group">
                        <h4 class="card-title">Add Todo</h4> &emsp;
                        <span id="insert_status"></span>
                    </div>
                    <div class="card-body p-4">
                        <form action="add_todo.php" method="POST" id="add_todo_form">
                            <?php
                            if ($_SESSION['login_status'] === 'SUCCESS') {
                                $todo  = array();
                                getFormContent($todo);
                            ?> 
                                <div class="bg-light mt-4 clearfix">
                                    <a href="all_todos.php" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                                    <input type="reset" class="btn btn-danger" value="Reset">
                                    <input type="submit" id="addTodo" name="addTodo" class="btn btn-primary float-end" value="Add Todo">
                                </div>
                            <?php
                            } else { ?>
                                <div class="bg-danger m-auto w-75 p-3 fw-bold fs-4"> <?php echo htmlentities(NEED_TO_LOGIN_MSG); ?> </div>
                            <?php
                            }
                            ?>
                            <hr>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php getFooter(); ?>
    <script type="text/javascript">
        "use strict";
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        $(document).ready(function() {
            $("#cover-spin").show().delay(500).fadeOut();
            //fucntion to perform submit of add_todo_form
            $("#add_todo_form").on("submit", function(event) {
                event.preventDefault();
                $("#cover-spin").show();
                const form = $(event.target);
                const json = convertFormToJSON(form);
                json["addTodo"] = "Add Todo";
                $.post("crud_todos.php", json, function(response) {
                    var data = JSON.parse(response);
                    if (data.status == "Success") {
                        $("#insert_status").html(
                            "<div class='alert alert-success'>" + sanitizeHTML(data.message) + "</div>"
                        ).delay(1000).fadeOut();
                        $("#add_todo_form")[0].reset();
                        setTimeout(function() {
                            window.location.replace("all_todos.php");
                        }, 1000);
                    } else {
                        $("#insert_status").html(
                            "<div class='alert alert-danger'>" + sanitizeHTML(data.message) + "</div>"
                        );
                    }
                }).fail(function() {
                    $("#cover-spin").delay(500).fadeOut();
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