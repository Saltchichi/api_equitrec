<?php
// Connect to database
include("db_connect.php");
$request_method = $_SERVER["REQUEST_METHOD"];

function getTrials()
{
    global $conn;
    $query = "SELECT * FROM Epreuves";
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $response[] = $row;
    }
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}
function addTrial()
{
    global $conn;
    $epreuve_nom = $_POST["epreuve_nom"];
    $userID = $_POST["UtilisateurID"];
    $categorieID = $_POST["categorieID"];
    $competitionID = $_POST["competitionID"];
    $created = date('Y-m-d H:i:s');
    $modified = date('Y-m-d H:i:s');
    echo $query = "INSERT INTO Epreuves(epreuve_nom, UtilisateurID, categorieID, competitionID, created, modified) VALUES('" . $epreuve_nom . "', '" . $userID . "', '" . $categorieID . "', '" . $competitionID . "', '" . $created . "', '" . $modified . "')";
    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'Epreuve ajouté avec succès.'
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
        getTrials();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;

    case 'POST':
        addTrial();
        break;
}
