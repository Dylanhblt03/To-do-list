<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/library/functions.inc.php');
$database = GET_DATABASE();
$dateDuJour = date('Y-m-d');

$database->execute_query("UPDATE tache SET titre = ?, position = ?, temps_passe = ?, estimation = ?,
description = ?, id_etat = ?, id_user_update = ?, date_update = ? WHERE id = ?", array(
    $_POST['titre'],
    $_POST['position'],
    $_POST['temps_passe'],
    $_POST['estimation'],
    $_POST['description'],
    $_POST['id_etat'],
    $_SESSION['id_utilisateur'],
    $dateDuJour,
    $_POST['id']
));