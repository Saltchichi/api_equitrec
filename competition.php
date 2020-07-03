<?php
// Connect to database
include("db_connect.php");
$request_method = $_SERVER["REQUEST_METHOD"];

function getCompetitions()
{
    global $conn;
    $query = "SELECT * FROM Competitions";
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $response[] = $row;
    }
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}
function addCompetition()
{
    global $conn;
    $competition_nom = $_POST["competition_nom"];
    $userID = $_POST["UtilisateurID"];
    $created = date('Y-m-d H:i:s');
    $modified = date('Y-m-d H:i:s');
    echo $query = "INSERT INTO Competitons(competition_nom, UtilisateurID, created, modified) VALUES('" . $competition_nom . "', '" . $userID . "', '" . $created . "', '" . $modified . "')";
    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'Competition ajouté avec succès.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'ERREUR!.' . mysqli_error($conn)
        );
    }
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json');
    echo json_encode($response);
}

switch ($request_method) {

    case 'GET':
        getCompetitions();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;

    case 'POST':
        addCompetition();
        break;
}
