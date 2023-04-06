<?php
require_once "pdo.php";
/**
 * @file - utility.php
 * Utility file which contains the common functions and constants used for the application.
 * Required pdo.php to run once so that we can access the database.
 */

session_start();
//All the constants defined here to be accessed across the files in application
const ERROR_404_MSG = "Error:404 Bad Request...";
const REGISTRATION_PAGE_MSG = "Please wait while we process your registration in 3 seconds...";
const NEED_TO_LOGIN_MSG = "Please login first to access the application.. ";
const REGISTRATION_FAIL_REDIRECT_MSG = "Regsitration is failed, redirecting to home page in 3seconds. Please try again...";
const LOGIN_FAIL_REDIRECT_MSG = "Login is failed, redirecting to home page in 3seconds. Please try again...";
const LOGIN_PAGE_MSG = "Please wait while we process your login request and redirect in 3 seconds...";
const LOGOUT_PAGE_MSG = "Please wait while we log you out and redirect in 3 seconds";
const INVALID_USER_CREDS_MSG = "Invalid Credentials, please try again. ";
const INVALID_PASSWORD_MSG = "Invalid Password, please try again";
const EMAIL_ALREADY_EXISTS_MSG = "Email already exists with us...";
const EMAIL_AVAILABLE_TO_USE_MSG = "Email is available to use.";
const EMAIL_INVALID_MSG = "Email is invalid, please use sample@domain.tld format.";
const INVALID_PARAM_MSG = "Invalid parameter";
const INVALID_ID_NO_TODO_MSG = "Invalid Id, No Todo is available or you don't have access to it";
const TODO_INSERT_SUCCESS_MSG = "Todo is created Successfully.";
const TODO_INSERT_FAIL_MSG = "Something went wrong, Todo is not created, please try again.";
const TODO_UPDATE_SUCCESS_MSG = "Todo is Updated Successfully.";
const TODO_UPDATE_FAIL_MSG = "Something went wrong, Todo is not updated, please try again.";
const TODO_STATUS_UPDATE_SUCCESS_MSG = "Todo status is updated successfully.";
const TODO_STATUS_UPDATE_FAIL_MSG = "Something went wrong, Todo status is not updated..";
const DELETE_TODO_SUCCESS_MSG =  "Todo record is deleted successfully.";
const DELETE_TODO_FAIL_MSG = "Error: No Records found to delete or you don't have access to it, please try again properly";
const NO_TODOS_AVAILABLE = "No todo's are available  to display..!!!";
const NO_TODOS_COMPLETED = "No todo's are completed to display..!!!";
//All locations stored as constants whilw we navigate through application
const INDEX_PAGE_LOCATION = '/';
const INDEX_LOGIN_PAGE_LOCATION = '/index.php#loginForm';
const ALL_TODO_LIST_PHP_LOCATION = '/controller/all_todos.php';



//Common method to execute the query and communication with the database for CRUD operations
function executeQuery($sql, $binded_params, $fetchMode)
{
    $pdo = dbConnection();
    $stmt = $pdo->prepare($sql);
    if ($fetchMode == "NONE" && $stmt->execute($binded_params)) {
        return true;
    } elseif ($fetchMode == "ONE" && $stmt->execute($binded_params)) {
        $row = $stmt->fetch();
        return $row;
    } elseif ($fetchMode == "ALL" && $stmt->execute($binded_params)) {
        $rows = $stmt->fetchAll();
        return $rows;
    } else {
        return false;
    }
}

//Method to fetch the current logged in user details
function getUserDetails($user_mail)
{
    $getCurrentUser = "SELECT user_id, fullname, email FROM users WHERE email = :mail";
    $param = array(
        "mail" => $user_mail
    );
    $currentUser = executeQuery($getCurrentUser, $param, "ONE");
    return $currentUser;
}



// Get Head function common head/meta tags/css/js inside todos application, once user logged in
function getHead()
{
    $pageTitle = dynamicTitle();
    $output = '<!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlentities($pageTitle) . '</title>

    <style>
        body {
            min-width: 300px;
            background: linear-gradient(to right, #f37099, #84d3ed);
        }

        /**http://www.menucool.com/9499/CSS-loading-spinner-with-a-semi-transparent-background*/
        #cover-spin {
            position: fixed;
            width: 100%;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            display: none;
        }

        @-webkit-keyframes spin {
            from {
                -webkit-transform: rotate(0deg);
            }

            to {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        #cover-spin::after {
            content: "";
            display: block;
            position: absolute;
            left: 48%;
            top: 40%;
            width: 40px;
            height: 40px;
            border-style: solid;
            border-color: black;
            border-top-color: transparent;
            border-width: 4px;
            border-radius: 50%;
            -webkit-animation: spin .8s linear infinite;
            animation: spin .8s linear infinite;
        }
        label.error {
            color: red;
            font-size: 0.8rem;
            display: block;
            text-align: left;
        
        }
        
        input.error{
            border: 1px solid red;
            font-weight: 300;
            color: red;
        }
    </style>

    
    <!-- Bootstrap CSS 5.2.2 Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    
    <!-- jQuery Libraries 3.6.1 Library with addtional validate method library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
        integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        "use strict";
        function convertFormToJSON(form) {
            return $(form)
                .serializeArray()
                .reduce(function(json, {
                    name,
                    value
                }) {
                    json[name] = value;
                    return json;
                }, {});
        }

        function sanitizeHTML(text) {
            return $("<span>").text(text).html();
        }
        </script>
    ';

    echo $output;
}


// Get Header function
function getHeader()
{
    $username = $_SESSION["user_fullname"];
    $output = '<header class="py-2 mb-3 border-bottom bg-white ">
        <div class="d-flex flex-wrap justify-content-center container">
            <a href="all_todos.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <span class="fs-5">Todo List, Welcome ' . htmlentities($username) . '!! </span>
            </a>
            <div class="nav nav-pills">
                <div class="nav-item"><a href="all_todos.php" class="nav-link btn btn-primary " aria-current="page">Home</a></div> &nbsp;&nbsp;
                <div class="nav-item"><a href="add_todo.php" class="btn btn-primary" id="add_todo_nav" name="add_todo_nav">Add Todo</a></div> &nbsp;
                <div class="nav-item"><a href="logout.php" class="btn btn-danger text-white">Logout</a></div>
            </div>
        </div>
        <div id="cover-spin"></div>
    </header>';

    echo $output;
}

//Get footer function
function getFooter()
{
    $output = '<!-- Footer -->
    <footer class="fixed-bottom bg-dark text-center text-white">
        <span>Developed by <a href="" class="text-info">Sasank Tipparaju & Jaya Chandu</a></span>
    </footer>
    ';
    echo $output;
}

//Input form elements function for creating / editing a record
function getFormContent($result)
{
    $mindate = date("Y-m-d");
    $mintime = date("h:i");
    $min = $mindate . "T" . $mintime;
    $output = '<!-- Form Input Elements -->
    <div class="mb-3">
        <label for="title" class="form-label">Title <sub><i>Max Characters allowed 150</i></sub></label>
        <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Create PPT for Web Applications Project" value="' . htmlentities($result['title']) . '" maxlength="150" required>
    </div>  
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3">' . htmlentities($result['description']) . '</textarea>
    </div>
    <div class="mb-3 row input-group">
        <div class="col-6">
            <label for="priority" class="form-label">Priority</label>
            <select name="priority" id="priority" class="form-control">
                <option value="Medium">Medium</option>
                <option value="High">High</option>
                <option value="Low">Low</option>
            </select>
        </div>
        <div class="col-6">
            <label for="category" class="form-label">Category</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="personal" value="Personal" checked>
                <label class="form-check-label" for="personal">Personal</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="work" value="Work">
                <label class="form-check-label" for="work">Work</label>
            </div>
        </div>
    </div>
    <div class="mb-4 row input-group">
        <div class="col-6">
            <label for="dueDate" class="form-label">Due Date</label>
            <input type="datetime-local" name="dueDate" id="dueDate" class="form-control" value="' . htmlentities($result['due_date']) . '" min="' . htmlentities($min) . '" required>
        </div>
        <div class="col-6">
            <label for="status" class="form-label">Status </label>
            <select name="status" id="status" class="form-control">
                <option value="Not Started">Not Started</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select>
        </div>

    </div>';
    echo $output;
}



// TextLimit function to truncate string on todos card with limit
function textLimit($string, $limit)
{
    if (strlen($string) > $limit) {
        return substr($string, 0, $limit) . "...";
    } else {
        return $string;
    }
}



//Get Todo function - to display each todo in form of card
function displayCard($todo)
{
    $isCompleted = htmlentities($todo['status']) == "Completed" ? "checked" : "";
    $output = '<div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-header">' . htmlentities(textLimit($todo['title'], 20)) . '</h5>
            <div class="card-body">
                <div class="card-subtitle"> 
                    <small class="text-warning">' . htmlentities($todo['status']) . '</small>
                    <small class=" m-2 text-info">' . htmlentities($todo['priority']) . '</small>
                    <small class=" m-2 text-muted">' . htmlentities($todo['category']) . '</small>
                </div>
                <p class="card-text">' . htmlentities(textLimit($todo['description'], 50)) . '</p>
                <sub class="card-text text-muted">Complete By: ' . date("F jS, Y e h:i A", strtotime(htmlentities($todo['due_date']))) . '</sub>
            </div>
            <div class="card-footer bg-transparent">
                <a href="view_todo.php?id=' . htmlentities($todo['todo_id']) . '" class="btn btn-sm btn-outline-secondary">View</a>
                <a href="edit_todo.php?id=' . htmlentities($todo['todo_id']) . '" class="btn btn-sm btn-outline-secondary">Edit</a>
               <span class="form-check form-switch float-end text-sm-start">
                    <input class="form-check-input" type="checkbox" role="switch" id="' . htmlentities($todo['todo_id']) . '" name="markComplete" onclick="updateStatus(id)"' . $isCompleted . ' style="cursor:pointer;">
                    <small class="form-check-label" for="markComplete">Mark Done</small>
                </span>
            </div>
        </div>
    </div>';

    return $output;
}

//Delete button with confirmation modal dialog used in view/edit_todo pages
function getDeleteButton($id)
{
    $output = ' 
    <a href="all_todos.php" id="cancel" name="cancel" class="btn btn-secondary">Go Back</a>
    <!-- Button trigger modal -->
    <input type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" value="Delete">
    <!-- Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteConfirmModalLabel">Delete Confirmation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this record, this action cannot be undone...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="delete_todo.php?id=' . htmlentities($id) . ' " class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>';
    echo $output;
}


// Dynamic Title function for each webpage
function dynamicTitle()
{
    $filename = basename($_SERVER["PHP_SELF"]);
    $pageTitle = "";
    switch ($filename) {
        case 'index.php':
            $pageTitle = "Home";
            break;

        case 'all_todos.php':
            $pageTitle = "Todo Dashboard";
            break;

        case 'add_todo.php':
            $pageTitle = "Add Todo List";
            break;

        case 'edit_todo.php':
            $pageTitle = "Edit Todo List";
            break;

        case 'view_todo.php':
            $pageTitle = "View Todo List";
            break;

        default:
            $pageTitle = "Todo List";
            break;
    }

    return $pageTitle;
}
