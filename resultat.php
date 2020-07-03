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

function getResults()
{
    global $conn;
    $query = "SELECT * FROM Resultats";
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $response[] = $row;
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}
function addResult($cap, $observation, $chute, $erreur)
{
    global $conn;
    if ($observation == "") {
        $observation = "RAS";
    }
    if ($chute == false) {
        $chute = "false";
    }
    if ($erreur == false) {
        $erreur = "false";
    }
    $query = "INSERT INTO Resultats(resultat_final, observations, chute, erreur_de_parcours) VALUES(" . $cap . ", '" . $observation . "', " . $chute . ", " . $erreur . ")";
    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'Resultat ajoute avec succes.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'ERREUR!.' . mysqli_error($conn)
        );
    }
    var_dump($query);
    echo json_encode($response);
}
switch ($request_method) {

    case 'GET':
        getResults();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
    case 'POST':
        $params = json_decode(file_get_contents("php://input"), true);
        addResult($params["cap"], $params["observation"], $params["chute"], $params["erreur"]);
        break;
}
