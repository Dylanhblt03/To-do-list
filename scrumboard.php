<?php
include('includes/header.inc.php');
$database = GET_DATABASE();
if (array_key_exists('id_utilisateur', $_SESSION)  && !is_null($_SESSION['id_utilisateur'])) {
    if (!array_key_exists('id_scrumboard', $_GET)) {
        // Partie création puisqu'on n'a pas d'ID
        if (!empty($_POST)) {
            if ($_POST['deadline'] == '') {
                $_POST['deadline'] = NULL;
            }
            $stmt = $database->prepare("INSERT INTO scrumboard (nomProjet, deadline, id_user_create, date_create) 
            VALUES (?, ?, ?, ?)");
            $dateDuJour = date('Y-m-d');
            $stmt->bind_param(
                "ssis",
                $_POST['nomProjet'],
                $_POST['deadline'],
                $_SESSION['id_utilisateur'],
                $dateDuJour
            );
            if ($stmt->execute()) {
                // Ici on sait que l'insertion du scrumboard a fonctionné
                $stmtEtat = $database->prepare("INSERT INTO etat (libelle, position, id_scrumboard, id_user_create, date_create) 
                VALUES (?, ?, ?, ?, ?), 
                (?, ?, ?, ?, ?), 
                (?, ?, ?, ?, ?)");
                $afaire = "A faire";
                $encours = "En cours";
                $terminee = "Terminée";
                $position1 = 1;
                $position2 = 2;
                $position3 = 3;
                $id_scrumboard = $stmt->insert_id;
                $stmtEtat->bind_param(
                    "siiissiiissiiis",
                    $afaire,
                    $position1,
                    $id_scrumboard,
                    $_SESSION['id_utilisateur'],
                    $dateDuJour,
                    $encours,
                    $position2,
                    $id_scrumboard,
                    $_SESSION['id_utilisateur'],
                    $dateDuJour,
                    $terminee,
                    $position3,
                    $id_scrumboard,
                    $_SESSION['id_utilisateur'],
                    $dateDuJour
                );
                $stmtEtat->execute();
                header('Location: scrumboard.php?id_scrumboard=' . $id_scrumboard);
            } else {
                echo "Insertion echouée";
            }
        }
?>
        <!-- On prévoit environ un quart de page par scrumboard -->
        <div class="container my-5">
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3">
                    <h1 class="text-center mb-4">Créer un scrumboard</h1>
                    <div class="card p-4 transparent">
                        <form action="/scrumboard.php" method="POST" name="tableau">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group my-3">
                                        <label for="nomProjet">Nom du projet</label>
                                        <input required="required" type="text" name="nomProjet" id="nomProjet" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group my-3">
                                        <label for="deadline">Deadline</label>
                                        <!-- L'attribut min permet de verrouiller les dates précédentes -->
                                        <input type="date" min="<?= date('Y-m-d') ?>" name="deadline" id="deadline" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center justify-content-md-center">
                                <button type="submit" class="btn" id="dropdownMenuButton1">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else {
        // Partie consultation
        $scrumboard = $database->execute_query("SELECT * 
        FROM scrumboard 
        WHERE id = ? ", array(
            $_GET['id_scrumboard']
        ))->fetch_assoc();
        
        $ratioCompletion = GET_POURCENTAGE_COMPLETION($scrumboard["id"]);
    ?>
        <div class="container my-3 card transparent">
            <div class="row">
                <div class="col-12 col-md-4 d-flex align-items-center justify-content-center">
                    <h1><?= $scrumboard['nomProjet'] ?></h1>
                </div>
                <div class="col-12 col-md-4 ">
                    <?php
                    // Calcul du pourcentage d'avancement
                    $pourcentage = $ratioCompletion['nbTachesTotal'] > 0 ? round(($ratioCompletion['nbTachesTerminees'] / $ratioCompletion['nbTachesTotal']) * 100) : 0;
                    // Couleur de la barre selon l'avancement
                    $couleurBarre = '';
                    if ($pourcentage < 30) {
                        $couleurBarre = 'linear-gradient(45deg, #7a5396ff, #451e63ff)'; // Rouge
                    } elseif ($pourcentage < 70) {
                        $couleurBarre = 'linear-gradient(45deg, #9075a3ff, #754f92ff)'; // Orange
                    } else {
                        $couleurBarre = 'linear-gradient(45deg, #dcbef1ff, #887398ff)'; // Vert
                    }
                    ?>
                    <?php
                    // Calcul du pourcentage d'avancement
                    if ($ratioCompletion['nbTachesTerminees'] > 0) { 
                    ?>
                        <!-- Barre de progression -->
                        <div>
                            <div class="progress-text">
                                Progression :
                            </div>
                            <div class="progress-container">
                                <div class="progress-bar-custom" style="width: <?= $ratioCompletion['pourcentage'] ?>%; background: <?= $couleurBarre ?>;">
                                    <?= $ratioCompletion['pourcentage'] ?>%
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-12 col-md-4 d-flex align-items-center justify-content-center">
                    <h4>Livraison : <?= FORMAT_DATE($scrumboard['deadline']) ?></h4>
                </div>
            </div>
        </div>
        <!-- FIN DE L'EN TÊTE -->
        <!-- DEBUT DE LA LISTE DES ETATS -->
        <?php
        //On veut tout les etats de ce scrumboard
        $listeEtats = $database->execute_query("SELECT *
        FROM etat
        WHERE id_scrumboard = ? 
        ORDER BY position", array(
            $_GET['id_scrumboard']
        ));
        ?>
        <div class="container overflow-x-auto d-flex align-items-start">
            <!-- DEBUT D'UN ETATS -->
            <?php
            foreach ($listeEtats as $etat) {
                $listeTaches = $database->execute_query("SELECT *
                FROM tache
                WHERE id_etat = ?
                AND status = 1
                ORDER BY position", array(
                    $etat['id']
                ));
            ?>
                <div class="card transparent etat d-inline-block mx-2" data-libelle="<?= $etat['libelle'] ?>" id="etat_<?= $etat['id'] ?>">
                    <!-- Dans l'en tête on serpare le nom de l'etat du nombres en les alignat et les collant de part et d'autre -->
                    <div class="enteteEtat d-flex justify-content-between align-items-center">
                        <h5 class="d-d-inline-block"><?= $etat['libelle'] ?></h5>
                        <h5 class="nbTaches">
                            <?php
                            // echo($listeTaches->num_rows > 1) ? $listeTaches->num_rows . " Tâches" : $listeTaches->num_rows . " Tâche";
                            if ($listeTaches->num_rows > 1) {
                                echo $listeTaches->num_rows . " Tâches";
                            } else {
                                echo $listeTaches->num_rows . " Tâche";
                            }
                            ?>
                        </h5>
                    </div>
                    <!-- Debut de la liste des taches dans l'etat en cours -->
                    <div class="listeTaches">
                        <!-- Debut d'une seul taches -->
                        <?php
                        foreach ($listeTaches as $tache) {
                            echo GENERER_HTML_TACHE($tache);
                        }
                        ?>
                        <!-- Fin de taches -->
                        <!-- Ajouter une tache -->
                        <button
                            type="button"
                            class="btn ajouterTache ajouterButton"
                            data-etat="<?= $etat['id'] ?>"
                            data-bs-toggle="modal"
                            data-bs-target="#formulaireTache">
                            Ajouter une tâche
                        </button>
                    </div>
                </div>
            <?php
            }
            ?>
            <!-- Fin de l'etat -->
        </div>
        <!-- MODALE D'AJOUT DE TÂCHE -->
    <?php
        include('includes/modaleAjouterTache.inc.php');
        include('includes/modaleModifierTache.inc.php');
    }
} else {
    ?>
    <div class="container my-5">
        <p class="text-dark text-center">
            Vous n'êtes pas autorisés à consulter cette page.
        </p>
    </div>
<?php
}



include('includes/footer.inc.php');
?>