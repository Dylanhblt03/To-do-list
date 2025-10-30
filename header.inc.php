<?php
session_start();
if(array_key_exists('logout', $_GET)) {
    session_destroy();
    header('Location: /');
}
// On se sert de $_SERVER['DOCUMENT_ROOT'] pour demarrer notre chemin depuis la racinesdu projet. En faisant ainsi, le chemin sera toujours
//le bon peux importe ou on decrit ce chemin puisqu'il partira d'une variable $_SERVEUR 
require_once($_SERVER['DOCUMENT_ROOT'] . '/library/functions.inc.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="images/favicon.png" type="image/png" size="32x32">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>LuxList</title>
</head>
<body>
    <header>
        <div class="navbar shadow-lg py-1" id="navbar">
            <div class="container">
                <div class="navbarrondi d-flex justify-content-between align-items-center" id="navbar1">
                    <a href="/index.php">
                        <img src="images/logo.png" alt="logo" class="img-fluid" id="logo">
                    </a>
                    <?php if(array_key_exists('id_utilisateur', $_SESSION)  && !is_null($_SESSION['id_utilisateur'])) { ?>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Mon compte
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="/profil.php">Mon profil</a></li>
                                <li><a class="dropdown-item" href="/?logout=1">Déconnexion</a></li>
                            </ul>
                        </div>
                    <?php } else { ?>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                               <i class="fa-solid fa-users text-white"></i> Connexion
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="/inscription.php">S'inscrire</a></li>
                                <li><a class="dropdown-item" href="/connexion.php">Se connecter</a></li>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </header>