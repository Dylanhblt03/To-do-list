<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/library/functions.inc.php');
$database = GET_DATABASE();


$tache = $database->execute_query("SELECT s.id as id_scrumboard, t.id_etat FROM scrumboard s
INNER JOIN etat e ON e.id_scrumboard = s.id
INNER JOIN tache t on t.id_etat = e.id
WHERE t.id = ?" , array($_POST['id_tache']))->fetch_assoc();


// On veut tout les etat de ce scrumboard
$listeEtats = $database->execute_query("SELECT *
        FROM etat
        WHERE id_scrumboard = ? 
        ORDER BY position", array(
            $tache['id_scrumboard']
        ))->fetch_all(MYSQLI_ASSOC);


$indexActuelleDeLaTache = NULL;
foreach($listeEtats as $index => $etatDuScrumboard) {
    if($etatDuScrumboard['id'] == $tache['id_etat']) {
        $indexActuelleDeLaTache = $index;
    }
}

if($_POST['direction'] == "precedent") {
    // Le nouvel Index sera laisser a 0 si il est deja a 0 sinon il sera juste diminue de 1
    $nouvelIndex = ($indexActuelleDeLaTache != 0) ? $indexActuelleDeLaTache- 1 : $indexActuelleDeLaTache;
} else {
    $nombreDetatDansMonTableau = count($listeEtats);
   $nouvelIndex = ($indexActuelleDeLaTache != $nombreDetatDansMonTableau - 1 ) ? $indexActuelleDeLaTache + 1 : $indexActuelleDeLaTache;
}

$nouvelEtat = $listeEtats[$nouvelIndex];
$database->execute_query("UPDATE tache SET id_etat = ? WHERE id = ?", array($nouvelEtat['id'], $_POST['id_tache']));

echo json_encode(array("id_nouvel_etat" => $nouvelEtat['id']));

