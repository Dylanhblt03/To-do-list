<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/library/functions.inc.php');
$database = GET_DATABASE();

$database->execute_query("UPDATE tache SET status = 0 WHERE id= ?", array($_POST['id_tache']));
?>