<!-- MON CODE ICI :) :) :)  -->
<?php
include('includes/header.inc.php');

if(!array_key_exists('id_utilisateur', $_SESSION) || is_null($_SESSION['id_utilisateur'])) {
    header('Location: /connexion.php');
}
$message = '';
$database = GET_DATABASE();

if(!empty($_POST)) {
    // Code qui ne s'exécute que lorsque le formulaire est envoyé
    if(trim($_POST['mot_de_passe']) == "") {
        // Si le mot de passe n'est pas mis à jour
        $requeteUpdate = "UPDATE utilisateur 
        SET nom = ?, 
        prenom = ?, 
        email = ?, 
        login = ?  
        WHERE id = ?";
        $arrayParams = array(
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            $_POST['login'],
            $_SESSION['id_utilisateur']
        );
    } else {
        $requeteUpdate = "UPDATE utilisateur 
        SET nom = ?, 
        prenom = ?, 
        email = ?, 
        login = ?, 
        mot_de_passe = ?
        WHERE id = ?";
        $arrayParams = array(
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            $_POST['login'], 
            password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT), 
            $_SESSION['id_utilisateur']
        );
    }

    $utilisateurExistant = $database->execute_query("SELECT * 
    FROM utilisateur 
    WHERE login = ? 
    AND id != ?", array(
        $_POST['login'], 
        $_SESSION['id_utilisateur']
    ))->fetch_assoc();

    if($utilisateurExistant == NULL) {
        // Si je ne trouve pas d'utilisateur existant avec le même Login
        if($database->execute_query($requeteUpdate, $arrayParams)) {
            // On réassigne la variable de session si il y a eu un changement de Login
            if($_SESSION['login'] != $_POST['login']) {
                $_SESSION['login'] = $_POST['login'];
            }
            $message = "Vos modifications ont bien été enregistrées.";
        } else {
            $message = "Une erreur est survenue, merci de réessayer ultérieurement.";
        }
    } else {
        // Si un utilisateur existe déjà avec ce login je gère un message d'erreur
        $message = "Ce login existe déjà.";
    }
}

$utilisateur = $database->execute_query("SELECT * 
FROM utilisateur 
WHERE id = ?", array(
    $_SESSION['id_utilisateur']
))->fetch_assoc();
?>

<div class="container my-5" id="inscription">

    <div class="text-center">
        <p><?= $message ?></p>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="card p-4 transparent">
                <form action="/profil.php" method="POST" name="profil">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="nom">Nom</label>
                                <input value="<?= $utilisateur['nom'] ?>" required="required" type="text" name="nom" id="nom" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="prenom">Prénom</label>
                                <input value="<?= $utilisateur['prenom'] ?>" required="required" type="text" name="prenom" id="prenom" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group my-3">
                        <label for="email">E-mail</label>
                        <input value="<?= $utilisateur['email'] ?>" required="required" type="text" name="email" id="email" class="form-control"/>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="login">Login</label>
                                <input value="<?= $utilisateur['login'] ?>" required="required" type="text" name="login" id="login" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="mot_de_passe">Mot de passe</label>
                                <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center justify-content-md-end">
                        <button type="submit" class="btn" id="dropdownMenuButton1">Mettre à jour mon profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
include('includes/footer.inc.php');
?>
?>