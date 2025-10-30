/**********************************************************************************
 * 
 * 
 *                         Ajout de tâche
 * 
 * 
 **********************************************************************************/


//Detection du click sur chaque boutton "ajouterButton"
$('.ajouterTache').on('click', function() {
    //On recupere la data 'etat' du boutton et on l'assigne au select de la modal
    $('#id_etat').val($(this).data('etat'));
    var nbTaches = $('#etat_' + $(this).data('etat') + ' .tache').length;
    // Ici on doit preciser un + avant chque chiffre de notre addition
    //Sinon Javascript va interpreter ça comme de la concatenantion
    //On peut donc se retroyer avec un 2 + 1 qui nous affihce 21 ...
    $('#position').val(+nbTaches + +1);
});

$('#ajouterTache').on('submit', function(event){
    // Que faire lorsque l'on détecte l'envoi du formulaire
    // On empêche le comportement par défaut du formulaire
    event.preventDefault();
    $.ajax({
        method: 'POST', 
        url: '/ajax/ajouterTache.ajax.php',
        data: $('#ajouterTache').serialize(),
        success: function(response) {
            var datajson = JSON.parse(response);
            if(datajson.insertionReussie == true) {
                // Action n 1: on masque la modal
                var modal = bootstrap.Modal.getInstance($('#formulaireTache'))
                modal.hide();
                // Action n 2: On colle le code HTML genere pour la nouvelle tache dans la liste correspondante
                $('#etat_' + $('#id_etat').val() + ' .ajouterTache').before(datajson.nouvelleTacheHTML);

                // Action n 3: On va mettre a jour le nombre de taches pour cette etat
                uptadeNombreDeTachesDansEtat($('#id_etat').val());
                // Reinisialister le formulaire a ses valeur par defaut
                $('#ajouterTache')[0].reset();
            } else {
                // On enleve les boutons de la modale pour empecher l'utilisateur de reessayer l'insertion
                $('.modal-footer').remove();
                // Et on affiche le message d'erreur a la place des input du formulaire
                $('modal-body').html("<p>" + datajson.message + "</p>");
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
});



/**********************************************************************************
 * 
 * 
 *                       Fin d'Ajout de tâche
 * 
 * 
 **********************************************************************************/

/**********************************************************************************
 * 
 * 
 *                          Gestion de la modification
 * 
 * 
 **********************************************************************************/

$('body').on('click', '.modifierTache', function(event) {
    // On empeche la propagation du click aux element derriere le crayon, comme la balise
    // par exemple. Cela va nous permette de gerer le clic sur le crayon
    // Sans declencher le click sur le lien <a>
    event.preventDefault();
    event.stopPropagation();
    $.ajax({
        method: 'POST',
        url: '/ajax/getTache.ajax.php',
        data: {id_tache: $(this).data('tache')},
        success: function(response) {
            var tache = JSON.parse(response);
            //Je transforme la cahine json_encode renvoyer pas l'ajax
            //en objet JSON
            //Puis je defini la valeur de chque input de la modale
            //En fonction des attribut de l'objet JSON
            $('#modifierTache #id').val(tache.id);
            $('#modifierTache #titreModification').val(tache.titre);
            $('#modifierTache #positionModification').val(tache.position);
            $('#modifierTache #temps_passeModification').val(tache.temps_passe);
            $('#modifierTache #estimationModification').val(tache.estimation);
            $('#modifierTache #descriptionModification').val(tache.description);
            $('#modifierTache #id_etatModification').val(tache.id_etat);
            
            // Et on affiche la modale
            $('#modalModifierTache').modal('show');
        }
    });
});

//on ecoute l'evenement du formulaire modifierTache
$('#modifierTache').on('submit', function(event){
    // Que faire lorsque l'on détecte l'envoi du formulaire
    // On empêche le comportement par défaut du formulaire
    event.preventDefault();
    $.ajax({
        method: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function(response) {
            window.location.reload();
        }
    });
});
/**********************************************************************************
 * 
 * 
 *                         Fin de Gestion de la modification
 * 
 * 
 **********************************************************************************/


/**********************************************************************************
 * 
 * 
 *                          Supression de tâche
 * 
 * 
 **********************************************************************************/

// On utilise cette synthaxe pour gerer le fait que les taches fraichement ajoutees dans le DOM
// Devront pouvoir etre supprimer aussi
$('body').on('click', '.suprimerTache', function(event) {
    // On empeche la propagation du click aux element derriere la poubelle, comme la balise
    // par exemple. Cela va nous permette de gerer le clic sur la poubelle
    // Sans declencher le click sur le lien <a>
    event.preventDefault();
    event.stopPropagation();
    var baliseAQuiContientMaTache = $(this).parent('div').parent('div').parent('a');
    var id_etat = $(this).data('etat');
    $.ajax({
        method: 'POST', // Comment ?
        url: '/ajax/suprimerTache.ajax.php', // OU ?
        data: { id_tache: $(this).data('tache')}, // Quoi ? la data tache qu'on a mit sur svg dans la balise <img> 
        success: function(response) {
            $(baliseAQuiContientMaTache).remove();
            uptadeNombreDeTachesDansEtat(id_etat);

        }
    });
});

/**********************************************************************************
 * 
 * 
 *                         Fin de Supression de tâche
 * 
 * 
 **********************************************************************************/

/**********************************************************************************
 * 
 * 
 *                         Function
 * 
 * 
 **********************************************************************************/

function uptadeNombreDeTachesDansEtat(id_etat) {
    // On compte le nombre de div qui ont la classe 'tache' dans la colone
    var nbTaches = $('#etat_' + id_etat + ' .tache').length;
    if(nbTaches > 1) {
        var texte = " Tâches";
    } else {
        var texte = " Tâche";
    }
    $('#etat_' + id_etat + ' .nbTaches').html(nbTaches + texte);

    uptadePourcentagesCompletions();
};

function uptadePourcentagesCompletions() {
    // On compte le nombre de taches dans tout le tableaux
    var nbTachesTotal = $('.tache').length;
    // On compte le nombre de tache dans la colone avecla data-libelle + terminee
    var nbTachesTerminees = $('div[data-libelle="Terminée"] .tache').length;
    // Puis on se sert de se porcentage pour defenir la largeur de nitre barre de progression
    var pourcentage = Math.round((nbTachesTerminees / nbTachesTotal) * 100, 2);

    // Et enfin on ecrit notre pourcentages dans la barre de progresion
    $('.progress-bar-custom').css('width', pourcentage + '%');
    $('.progress-bar-custom').html(pourcentage + '%');
};

/**********************************************************************************
 * 
 * 
 *                         Function
 * 
 * 
 **********************************************************************************/


/**********************************************************************************
 * 
 * 
 *                        Gestion Deplacement de la tâche
 * 
 * 
 **********************************************************************************/

$('body').on('click', '.fleche', function(e){
    e.preventDefault();
    e.stopPropagation();
    var baliseAQuiContientMaTache = $(this).parent('div').parent('a');
    var etatDepart = $(baliseAQuiContientMaTache).parent('div').parent('div');
    var id_etat_depart = $(etatDepart).attr('id').split("_")[1];
    $.ajax({
        method: 'POST',
        url: '/ajax/deplacerTache.ajax.php',
        data: {
            id_tache: $(this).data('tache'),
            direction: $(this).data('direction')
        },
        success: function(response) {
            var data = JSON.parse(response);
            var id_etat_destination = data.id_nouvel_etat;
            $(baliseAQuiContientMaTache).insertBefore('#etat_' + id_etat_destination + ' .ajouterTache');
            uptadeNombreDeTachesDansEtat(id_etat_depart);
            uptadeNombreDeTachesDansEtat(id_etat_destination);
        }
    });
});



/**********************************************************************************
 * 
 * 
 *                        Fin de Gestion Deplacement de la tâche
 * 
 * 
 **********************************************************************************/