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

function getCompetiteurs()
{
    global $conn;
    $query = "SELECT * FROM Competiteurs";
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $response[] = $row;
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}
function getCompetiteurByDossard($dossard)
{
    global $conn;
    $query = "SELECT * FROM Utilisateurs";
    if ($dossard) {
        $query .= " WHERE num_dossard=" . $dossard . " LIMIT 1";
    }
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $response[] = $row;
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}
function addCompetiteur()
{
    global $conn;
    $num_dossard = $_POST["num_dossard"];
    $created = date('Y-m-d H:i:s');
    $modified = date('Y-m-d H:i:s');
    echo $query = "INSERT INTO Competiteurs(num_dossard, created, modified) VALUES('" . $num_dossard . "', '" . $created . "', '" . $modified . "')";
    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'Competiteur ajouté avec succès.'
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
        $params = json_decode(file_get_contents("php://input"), true);
        if (!empty($params["dossard"])) {
            getUserByMail($dossard);
        } else {
            getCompetiteurs();
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;

    case 'POST':
        addCompetiteur();
        break;
}
