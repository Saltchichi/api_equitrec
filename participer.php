<?php
// Connect to database
include("db_connect.php");
$request_method = $_SERVER["REQUEST_METHOD"];

function getTrials()
{
    global $conn;
    $query = "SELECT * FROM Participer";
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $response[] = $row;
    }
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}

switch ($request_method) {

    case 'GET':
        getTrials();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
