<?php
include('includes/header.inc.php');
?>

<div class="container my-5">
    <?php
    if (!array_key_exists("id_utilisateur", $_SESSION) || is_null($_SESSION['id_utilisateur'])) {
    ?>
        <div class="row">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card p-4 transparent">
                    <p class="text-center">
                        Connectez-vous pour accéder à vos tableaux : <a href="/connexion.php" class="btn" id="dropdownMenuButton1">Connexion</a>
                    </p>
                </div>
            </div>
        </div>
    <?php
    } else {
        // Ici on a un utilisateur connecté donc on peut commencer à lui lister ses tableaux. 
        $database = GET_DATABASE();

        $listeScrumboards = GET_LISTE_SCRUMBOARDS_USER($_SESSION['id_utilisateur']);
    ?>
        <div class="row">
            <?php
            // Une fois la requête executée, j'utilise un while pour stocker chaque ligne existante dans une variable $scrumboard
            while ($scrumboard = $listeScrumboards->fetch_assoc()) {
                echo GENERER_HTML_SCRUMBOARD($scrumboard);
            }
            ?>
            <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end justify-content-end">
                <a href="scrumboard.php" class="h-25 w-25 btn btn-primary d-flex align-items-center justify-content-center" id="addScrumboard">
                    +
                </a>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<?php
include('includes/footer.inc.php')
?>