<?php
/**
 * @file - pdo.php
 * file to establish connection to database server
 */
const DB_HOST = 'db4free.net';
const DB_PORT = 3306;
const DB_NAME = 'todo_list_db';
const DB_USER = 'admin4db';
const DB_PASSWORD = 'nm@wUEx6su2nKQ9';
const DB_CONN_ERROR = "<b>Something went wrong with database connection</b>, Please contact admin with the error message as...: ";
//Setting default time zone for the application in server !
date_default_timezone_set('America/Chicago');
function dbConnection()
{
    $connString = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    try {
        $databaseConnection = new PDO($connString, DB_USER, DB_PASSWORD);
        // See the "errors" folder for details...
        $databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //setting the FETCH_ASSOC as the default fetch mode
        $databaseConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $databaseConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return  $databaseConnection;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        echo DB_CONN_ERROR.$message;
    }
}


