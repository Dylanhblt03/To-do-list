<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/library/functions.inc.php');
$database = GET_DATABASE();

$dateDuJour = date('Y-m-d');

$stmt = $database->prepare("INSERT INTO tache (titre, description, estimation, temps_passe, position, id_etat, id_user_create, date_create)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssddiiis",
    $_POST['titre'], 
    $_POST['description'], 
    $_POST['estimation'], 
    $_POST['temps_passe'], 
    $_POST['position'], 
    $_POST['id_etat'], 
    $_SESSION['id_utilisateur'],
    $dateDuJour
);
if($stmt->execute()) {
    $insertionReussie = true;
    $message = "Votre tâche a bien été créée !";

    $tache = array (
        "id" => $stmt->insert_id,
        "titre" => $_POST['titre'],
        "description" => $_POST['description'],
        "temps_passe" => $_POST['temps_passe'],
        "estimation" => $_POST['estimation'],
        "id_etat" => $_POST['id_etat']
    );

    $nouvelleTacheHTML = GENERER_HTML_TACHE($tache);

} else {
    $insertionReussie = false;
    $message = "Une erreur est survenue !";
    $nouvelleTacheHTML = "";
}

echo json_encode(array(
    "insertionReussie" => $insertionReussie,
    "message" => $message,
    "nouvelleTacheHTML" => $nouvelleTacheHTML
));