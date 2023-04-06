<?php
require_once "../includes/utility.php";
/**
 * Location: To-Do List/Controller/crud_todos.php
 * @file crud_todos.php
 * This file is to perform the create and update todos action and also mark complete action
 * requires utility.php to run database and common code for the application.
 */
session_start();
$msg = '';
$response = array();
if (isset($_SESSION['login_status']) && $_SESSION['login_status'] == 'SUCCESS') {
    if (isset($_POST['addTodo']) && $_POST['addTodo'] = 'Add Todo' && isset($_POST['title']) && $_POST['title']) {
        try {
            $title = htmlentities($_POST['title']);
            $description = htmlentities($_POST['description']);
            $priority = $_POST['priority'] == '' ? 'Medium' :  htmlentities($_POST['priority']);
            $status = $_POST['status'] == '' ? 'Not Started' :  htmlentities($_POST['status']);
            $category = $_POST['category'] == '' ? 'Personal' :  htmlentities($_POST['category']);
            $due = htmlentities($_POST['dueDate']);
            $dueDate = date("Y-m-d H:i:s", strtotime($due));
            $currentUserData = getUserDetails($_SESSION['user_mail']);
            if ($currentUserData['user_id'] != '') {
                $insertQuery  = "INSERT INTO todos (title, description, priority, status, category, due_date, user_id) VALUES (:title, :desc, :prior, :stat, :cat, :due, :uid)";
                $insertBindParams = array(
                    "title" => $title,
                    "desc" => $description,
                    "prior" => $priority,
                    "stat" => $status,
                    "cat" => $category,
                    "due" => $dueDate,
                    "uid" => $currentUserData['user_id']
                );
                $res = executeQuery($insertQuery, $insertBindParams, "NONE");

                if ($res) {
                    $response = array(
                        "status" => "Success",
                        "message" => TODO_INSERT_SUCCESS_MSG
                    );
                } else {
                    $response = array(
                        "status" => "Fail",
                        "message" => TODO_INSERT_FAIL_MSG
                    );
                }
            } else {
                $response = array(
                    "status" => "fail",
                    "message" => TODO_INSERT_FAIL_MSG
                );
            }
            echo json_encode($response);
        } catch (Exception $e) {
            $response = array(
                "status" => "Fail",
                "message" => TODO_INSERT_FAIL_MSG . "Exception Code:" . $e->getCode(),
                "exception" => $e->getMessage()
            );
            echo json_encode($response);
        }
    } elseif (isset($_POST['updateTodo']) && $_POST['updateTodo'] = 'Update Todo' && isset($_POST['todoId']) && isset($_POST['title'])) {
        try {
            $id = htmlentities($_POST['todoId']);
            $title = htmlentities($_POST['title']);
            $description = htmlentities($_POST['description']);
            $priority = $_POST['priority'] == '' ? 'Medium' :  htmlentities($_POST['priority']);
            $status = $_POST['status'] == '' ? 'Not Started' :  htmlentities($_POST['status']);
            $category = $_POST['category'] == '' ? 'Personal' :  htmlentities($_POST['category']);
            $due = htmlentities($_POST['dueDate']);
            $dueDate = date("Y-m-d H:i:s", strtotime($due));
            $currentUserData = getUserDetails($_SESSION['user_mail']);
            if ($currentUserData['user_id'] != '') {
                $updateQuery  = "UPDATE todos SET title=:title, description=:desc, priority=:prior, status=:stat, category=:cat, due_date=:due  WHERE todo_id =:id AND user_id =:uid";
                $updateParams = array(
                    "title" => $title,
                    "desc" => $description,
                    "prior" => $priority,
                    "stat" => $status,
                    "cat" => $category,
                    "due" => $dueDate,
                    "id" => $id,
                    "uid" => $currentUserData['user_id']
                );
                $res = executeQuery($updateQuery, $updateParams, "NONE");
                if ($res) {
                    $response = array(
                        "status" => "Success",
                        "message" => TODO_UPDATE_SUCCESS_MSG
                    );
                } else {
                    $response = array(
                        "status" => "Fail",
                        "message" => TODO_UPDATE_FAIL_MSG
                    );
                }
            } else {
                $response = array(
                    "status" => "Fail",
                    "message" => TODO_UPDATE_FAIL_MSG
                );
            }
            echo json_encode($response);
        } catch (Exception $e) {
            $response = array(
                "status" => "Fail",
                "message" => TODO_UPDATE_FAIL_MSG . "Exception Code: " . $e->getCode(),
                "exception" => $e->getMessage()
            );
            echo json_encode($response);
        }
    } elseif (isset($_POST['markComplete']) && $_POST['markComplete'] == "Clicked" && isset($_POST['id']) && !empty($_POST['id'])) {
        try {
            $todoId = htmlentities($_POST['id']);
            $currUser = getUserDetails($_SESSION['user_mail']);
            $sql = "SELECT todo_id, title, status FROM todos WHERE todo_id = :id AND user_id =:uid";
            $params = array(
                "id" => $todoId,
                "uid" => $currUser['user_id']
            );
            $row = executeQuery($sql, $params, "ONE");
            $title = $row['title'];
            if ($row) {
                $usql = "UPDATE todos SET status =:stat WHERE todo_id =:id AND user_id =:uid";
                $status =  $row['status'] != 'Completed'? 'Completed' : 'Not Started';
                $uparams = array(
                    "stat" => $status,
                    "id" => $todoId,
                    "uid" => $currUser['user_id']
                );
                $res = executeQuery($usql, $uparams, "NONE");
                if ($res) {
                    $response = array(
                        "status" => "Success",
                        "message" => TODO_STATUS_UPDATE_SUCCESS_MSG
                    );
                } else {
                    $response = array(
                        "status" => "Fail",
                        "message" => TODO_STATUS_UPDATE_FAIL_MSG
                    );
                }
            } else {
                $response = array(
                    "status" => "Fail",
                    "message" => ERROR_404_MSG . TODO_STATUS_UPDATE_FAIL_MSG
                );
            }
            echo json_encode($response);
        } catch (Exception $e) {
            $response = array(
                "status" => "Fail",
                "message" => ERROR_404_MSG . 'Exception: ' . $e->getMessage()
            );
            echo json_encode($response);
        }
    } else {
        header("refresh:0;url=" . ALL_TODO_LIST_PHP_LOCATION);
    }
} else {
    echo ERROR_404_MSG;
    header("refresh:1;url=" . INDEX_PAGE_LOCATION);
}
$_POST = array();
die();
