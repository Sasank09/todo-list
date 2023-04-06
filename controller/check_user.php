<?php

/**
 * location: To-Do List/Controller/check_user.php
 * called by Javascript todo_list.js Asynchronous call to check_user available status
 * @var mail - gets mail id from the encoded url of getJSON Request in todo_list.js checkUserMailAvailability
 * usage: Logic to check if email Id is valid and already exists in database or not
 * @return json encoded response with status and message
 */

require_once "../includes/utility.php";
header('Content-Type: application/json; charset=utf-8');
if (isset($_GET["email"]) && !empty($_GET["email"])) {
    $mail = $_GET["email"];
    $sql = "SELECT * FROM users WHERE email=:mail";
    $arry = array(
        'mail' => $mail,
    );
    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        if (executeQuery($sql, $arry, "ONE")) {
            $response = array(
                "status" => 0,
                "message" => EMAIL_ALREADY_EXISTS_MSG
            );
        } else {
            $response = array(
                "status" => 1,
                "message" => EMAIL_AVAILABLE_TO_USE_MSG
            );
        }
        echo json_encode($response);
    } else {
        $response = array(
            "status" => 0,
            "message" => EMAIL_INVALID_MSG
        );
        echo json_encode($response);
    }
    die();
}
