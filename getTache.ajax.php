<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/library/functions.inc.php');
$database = GET_DATABASE();

$tache = $database->execute_query("SELECT * FROM tache WHERE id = ?", array($_POST['id_tache']))->fetch_assoc();

echo json_encode($tache);