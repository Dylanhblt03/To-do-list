<?php
include('includes/header.inc.php');

$message = '';
if(!empty($_POST)) {
    $database = GET_DATABASE();
    //Gerer la connexion ici si $_POST n'est pas vide
    $requetUtilisateur = "SELECT * FROM utilisateur WHERE login = ?";
    
    $utilisateur = $database->execute_query($requetUtilisateur, array(
        $_POST['login']
    ))->fetch_assoc();

    if($utilisateur != NULL) {
        //Si on a trouvé un utilisateur
        if(password_verify($_POST['mot_de_passe'], $utilisateur['mot_de_passe'])) {
            //Si le mot de passe est bien le bon
            $_SESSION['id_utilisateur'] = $utilisateur['id'];
            $_SESSION['login'] = $utilisateur['login'];
            header('Location: /connexion.php');
        } else {
            $message = "Informations de connexion incorrectes";
        }
    } else {
        //Sinon
        $message = "Informations de connexion incorrectes";
    }
}

?>

<div class="container my-5">
    <div class="row">
        <div class="col-12 col-md-6 offset-md-3">
            <p class="text-center text-danger"><?=$message?></p>
            <?php
            //Si a clé id_utilisateur existe dans $_SESSION
            //Alors on souhaite la bienveue à l'utilisateur
            if(array_key_exists('id_utilisateur', $_SESSION) && !is_null($_SESSION['id_utilisateur'])) {
                ?>
                <h1 class="text-center">
                    Bienvenue <?= $_SESSION['login']?>
                </h1>
                <div class="d-flex align-items-center justify-content-center">
                    <a href="/" class="btn mx-4" id="dropdownMenuButton1">Consulter mes tableaux</a>
                    <a href="/profil.php" class="btn mx-4" id="dropdownMenuButton1">Mon profil</a>
                </div>
                <?php
            } else {
            ?>
            <h1 id="formconn" class="text-center mb-4">Formulaire de connexion</h1>
            <p class="text-center">Connectez-vous pour accéder à vos tableaux</p>
            <div class="card p-4 transparent">
                <form action="/connexion.php" method="POST" name="connexion">
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
                        <button type="submit" class="w-100 btn" id="dropdownMenuButton1">Connexion</button>
                    </div>
                </form>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>











<?php
include('includes/footer.inc.php')
?>