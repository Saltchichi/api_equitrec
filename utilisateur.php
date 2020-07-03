<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}

// Connect to database
include("db_connect.php");
$request_method = $_SERVER["REQUEST_METHOD"];

function getUsers()
{
    global $conn;
    $query = "SELECT * FROM Utilisateurs";
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $response[] = $row;
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}

function checkLogin($email, $password)
{
    global $conn;
    $query = "SELECT motdepasse FROM Utilisateurs WHERE email = '" . $email . "'";
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $response = $row;
    }
    if (password_verify($password, $response["motdepasse"])) {
        echo json_encode(true, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(false, JSON_PRETTY_PRINT);
    }
}

function getUserByMail($email)
{
    global $conn;
    $query = "SELECT * FROM Utilisateurs";
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $query .= " WHERE email=" . $email . " LIMIT 1";
    }
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $response[] = $row;
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}

function addUser()
{
    global $conn;
    $roleID = $_POST["roleID"];
    $email = $_POST["email"];
    $motdepasse = $_POST["motdepasse"];
    $created = date('Y-m-d H:i:s');
    $modified = date('Y-m-d H:i:s');
    echo $query = "INSERT INTO Utilisateurs(roleID, email, motdepasse, created, modified) VALUES('" . $email . "', '" . $motdepasse . "', '" . $roleID . "', '" . $created . "', '" . $modified . "')";
    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'Utilisateur ajouté avec succès.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'ERREUR!.' . mysqli_error($conn)
        );
    }
    echo json_encode($response);
}

switch ($request_method) {

    case 'GET':
        if (!empty($_GET["email"])) {
            getUserByMail($email);
        } else {
            getUsers();
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;

    case 'POST':
        $params = json_decode(file_get_contents("php://input"), true);
        checkLogin($params["email"], $params["motdepasse"]);
        break;
}
