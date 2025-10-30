<?php
include('includes/header.inc.php');
$message = "";
//Code qui ne s'execute que lorsque le formulaire et envoyer
if(!empty($_POST)) {
    //Par defaut on n'autorise l'insertion puis on verifie que les champs sont bien remplie
    $autoriserInsertion = true;
    foreach($_POST as $key => $value) {
        if($value == "") {
            //Et si on a ne serait-ce qu'une seule valeur vide, ça nous suffit pour passer la variable à false
            $autoriserInsertion = false;
        }
    }
    // Code qui ne s'exécute que si tous les champs sont bien remplis ($autoriserInsertion == true)
    if($autoriserInsertion) {
        $database = GET_DATABASE();

    $utiliateur = $database->execute_query("SELECT * FROM utilisateur WHERE login =?", array(
        $_POST['login']
    ))->fetch_assoc();
    //Si on n'a trouveé aucun résultat en base, alors ça veut dire que le login est disponible
    if($utiliateur == NULL) {
    //Ici on "hash" le mot de passe pour le crypter (60 caractere par defeaut)
    $hashed_password = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $stmt = $database->prepare("INSERT INTO utilisateur (nom, prenom, email, login, mot_de_passe)
    VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss",
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['email'],
        $_POST['login'],
        $hashed_password
    );
    if ($stmt->execute() == true) {
        $message = "Votre compte a été créé avec succés. Vous pouvez vous connecter avec le lien suivant : ";
        $message .= "<a href='/connexion.php' class= 'btn btn-danger'>Se connecter</a>";
    } else {
        $message = "Une erreur est survenue.";
    };
} else {
    $message = 'Ce login existe déjà';
        }
    } else {
        $message = "Merci de bien renseigner tous les champs.";
    } // Fermeture du if($autoriserInsertion)
} // Fermeture du if(!empty($_POST))


?>



<div class="container my-5" id="inscription">
    <div class="text-center">
        <p><?= $message ?></p>
    </div>
    <div class="row">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="card p-4 transparent">
                <form action="/inscription.php" method="POST" name="inscription">
                    <div class="row">               
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="nom">Nom</label>
                                <input required="required" type="text" name="nom" id="nom" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="prenom">Prénom</label>
                                <input required="required" type="text" name="prenom" id="prenom" class="form-control">
                            </div>
                        </div>
                        <div class="form-group my-3">
                            <label for="email">E-mail</label>
                            <input required="required" type="text" name="email" id="email" class="form-control">
                        </div>
                    </div>
                    <div class="row">               
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="login">Login</label>
                                <input required="required" type="text" name="login" id="login" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="mot_de_passe">Mot de passe</label>
                                <input required="required" type="password" name="mot_de_passe" id="mot_de_passe" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="m-1">
                        <button type="submit" class="w-100 btn btn-danger" id="dropdownMenuButton1">S'inscrire</button>
                    </div>
                </form>    
            </div>
        </div>
    </div>
</div>












<?php
include('includes/footer.inc.php')
?>