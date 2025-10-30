<?php

/**
 * Genere le contenu HTML correspondant a une tâche
 */
function GENERER_HTML_TACHE(array $maTache)
{
    // On ouvre le buffer de sortie de PHP
    ob_start();

?>
    <a href="#" class="tache card my-3 p-3">
        <div class="d-flex justify-content-between">
            <h6 class="titreTaches"><?= $maTache['titre'] ?></h6>
        <div>
            <img src="/images/pencil.png" alt="updade_logo" class="modifierTache" data-tache="<?= $maTache['id'] ?>"/>
            <img src="/images/trash.svg" class="suprimerTache img-svg" alt="trach" data-etat="<?= $maTache['id_etat'] ?>" data-tache="<?= $maTache['id'] ?>">
        </div>
        </div>
        <p><?= $maTache['description'] ?></p>
        <div class="d-flex justify-content-between">
            <div class="tempsPasse mx-2">
                Temps passé : <?= $maTache['temps_passe'] ?>h
            </div>
            <div class="estimation mx-2">
                Estimation : <?= $maTache['estimation'] ?>h
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <img src="/images/fleche.png" data-direction="precedent" data-tache="<?= $maTache['id'] ?>" class="fleche previous img-fluid"/>
            <img src="/images/fleche.png" data-direction="suivant" data-tache="<?= $maTache['id'] ?>" class="fleche img-fluid"/>
        </div>
    </a>
<?php
    // On stocke le contenue de l'OB dans une variable et on nettoie l'OB
    $taccheHTML = ob_get_clean();
    return $taccheHTML;
}


/**
 * Renvoie un objet de la classe msqli permettant d'interagir avec la base donnee
 */
function GET_DATABASE()
{
    // Function qui sert a se lier a la base donner ($database = GET_DATABASE();)
    $database = new mysqli("192.168.56.56", "homestead", "secret", "to-do-list");
    mysqli_set_charset($database, "utf8mb4");
    return $database;
}


/**
 * Ultile l'id du scrumboard pour calculer le taux de completion et renvoyer le pourcentages + le nombre de tâches terminer
 */
function GET_POURCENTAGE_COMPLETION(int $id_scrumboard)
{
    // je declare la function database pour qu'elle soit reconue dans cette fonction
    $database = GET_DATABASE();
    // CALCUL DU TAUX DE COMPLETION
    // Ici on compte le nb de tâche du scrumboard en cours d'itération
    // On execute la requête, on met le résultat dans un tableau associatif, et on prend la clé "totalTache" du tableau associatif
    $nbTachesTotal = $database->execute_query("SELECT COUNT(*) as totalTache
        FROM tache 
        INNER JOIN etat ON etat.id = tache.id_etat 
        INNER JOIN scrumboard ON scrumboard.id = etat.id_scrumboard
        WHERE scrumboard.id = ?
        AND status = 1 ;", array(
        $id_scrumboard
    ))->fetch_assoc()['totalTache'];

    // Cette fois on lui demande le nb de tâche qui ont un état "Terminée"
    // Et on lui demande le champ "totalTacheTerminee" dans le tableau associatif
    $nbTachesTerminees = $database->execute_query("SELECT COUNT(*) as totalTacheTerminee
        FROM tache 
        INNER JOIN etat ON etat.id = tache.id_etat 
        INNER JOIN scrumboard ON scrumboard.id = etat.id_scrumboard 
        WHERE scrumboard.id = ?
        AND status = 1 
        AND etat.libelle = \"Terminée\";", array(
        $id_scrumboard
    ))->fetch_assoc()['totalTacheTerminee'];

    // Calcul du pourcentage d'avancement
    if ($nbTachesTerminees > 0) {
        $pourcentage = round(($nbTachesTerminees / $nbTachesTotal) * 100);
    } else {
        $pourcentage = 0;
    }

    return array(
        'nbTachesTotal' => $nbTachesTotal,
        'nbTachesTerminees' => $nbTachesTerminees,
        "pourcentage" => $pourcentage
    );
}

/**
 * Formate une date au format jj//mm/AAAA ou renvoie une chaine vide si date nulle
 */
function FORMAT_DATE($date_a_convertir)
{
    //FORMATTAGE DE LA DATE
    // Si on a une date précisée en base alors on la formate au format dd/mm/YYYY
    if ($date_a_convertir != NULL) {
        $tableauDate = explode("-", $date_a_convertir);
        $nouveauFormatDeDate = implode("/", array_reverse($tableauDate));
    } else {
        // Sinon on met une chaîne vide à la place
        $nouveauFormatDeDate = "";
    }

    return $nouveauFormatDeDate;
}



/**
 * On donne un scrumboard en entrer et on recupere son contenue en HTML
 */
function GENERER_HTML_SCRUMBOARD(array $monScrumboard) {
                    // Si on a une deadline précisée en base alors on la formate au format dd/mm/YYYY
                $nouveauFormatDeDate = FORMAT_DATE($monScrumboard['deadline']);
                
                $ratioCompletion = GET_POURCENTAGE_COMPLETION($monScrumboard["id"]);

                ob_start();

                // Couleur de la barre selon l'avancement
                $couleurBarre = '';
                if ($ratioCompletion['pourcentage'] < 30) {
                    $couleurBarre = 'linear-gradient(45deg, #6b4586ff, #451e63ff)'; // Rouge
                } elseif ($ratioCompletion['pourcentage'] < 70) {
                    $couleurBarre = 'linear-gradient(45deg, #9277a5ff, #754f92ff)'; // Orange
                } else {
                    $couleurBarre = 'linear-gradient(45deg, #dcbef1ff, #887398ff)'; // Vert
                }

            ?>
                <div class="col-12 col-md-6 col-lg-3 ">
                    <a href="scrumboard.php?id_scrumboard=<?= $monScrumboard['id'] ?>" class="itemScrumboard card transparent py-3 h-100 d-flex flex-column justify-content-between">
                        <h3 class="mb-3"><?= $monScrumboard['nomProjet'] ?></h3>

                        <?php if ($nouveauFormatDeDate): ?>
                            <h5 class="text-muted mb-3">📅 <?= $nouveauFormatDeDate ?></h5>
                        <?php endif; ?>

                        <!-- Informations sur les tâches -->
                        <div class="mb-3">
                            <small class="text-muted">
                                <?= $ratioCompletion['nbTachesTotal'] ?> / <?= $ratioCompletion['nbTachesTerminees'] ?> tâches terminées
                            </small>
                        </div>

                        <?php
                        // Calcul du pourcentage d'avancement
                        if ($ratioCompletion['nbTachesTerminees'] > 0) {
                        ?>
                            <!-- Barre de progression -->
                            <div class="progress-text">
                                Progression : <?= $ratioCompletion['pourcentage'] ?>%
                            </div>
                            <div class="progress-container">
                                <div class="progress-bar-custom"
                                    style="width: <?= $ratioCompletion['pourcentage'] ?>%; background: <?= $couleurBarre ?>;">
                                    <?= $ratioCompletion['pourcentage'] ?>%
                                </div>
                            </div>
                        <?php
                        }

                        ?>
                        <!-- Badge de statut -->
                        <?php if ($ratioCompletion['pourcentage'] == 100): ?>
                            <span class="badge" id="traitement">✅ Terminé</span>
                        <?php elseif ($ratioCompletion['pourcentage'] >= 70): ?>
                            <span class="badge" id="traitement">🔜 Bientôt fini</span>
                        <?php elseif ($ratioCompletion['pourcentage'] >= 10): ?>
                            <span class="badge" id="traitement">⌛ En cours</span>
                        <?php else: ?>
                            <span class="badge" id="traitement">📝 À commencer</span>
                        <?php endif; ?>
                    </a>
                </div>
    <?php
    $scrumboardHTML = ob_get_clean();
    return $scrumboardHTML;
}


/**
 * On donne en entrer l'id utilisateur et on recupere la liste de ses scrumboard en sortie
 */
function GET_LISTE_SCRUMBOARDS_USER(int $id_utilisateur) {
        $database = GET_DATABASE();
            // On va lui lister les tableaux qui : 
        // - ont été créés par lui (champ id_user_create)
        // - contiennent des tâches qui ont été créées par l'utilisateur (champ id_user_create)
        // - contiennent des tâches sur lesquelles l'utilisateur est affecté (table tache_utilisateur)
        $listeScrumboards = $database->execute_query("SELECT DISTINCT s.* 
        FROM scrumboard s 
        INNER JOIN etat e ON e.id_scrumboard = s.id 
        LEFT JOIN tache t ON t.id_etat = e.id 
        LEFT JOIN tache_utilisateur tu ON tu.id_tache = t.id 
        WHERE s.id_user_create = ? 
        OR t.id_user_create = ?
        OR tu.id_utilisateur = ?", array(
            $id_utilisateur,
            $id_utilisateur,
            $id_utilisateur

        ));
           return $listeScrumboards;
}








    ?>