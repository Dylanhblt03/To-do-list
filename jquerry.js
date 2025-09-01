$(function() {
    $("#ajouter").on("click", function() {
        var nouvelleTache = $("#tache").val();
        var liElement = "<li class=\"m-1 hugo\">" + nouvelleTache + " <button class='supprimer btn btn-danger m-2'>Supprimer</button></li>";
        if (nouvelleTache.trim() !== "") {
            $("#liste").append(liElement);
            $("#tache").val("");
        };
        if ($("#liste li").length >= 1) {
            $("#fin").addClass("d-none");
    }
       
    });


//Bouton pour surpprimer les taches

    $(document).on('click', '.supprimer', function(){ 
        $(this).parent().remove();

         if($("#liste li").length == 0) {
            $("#fin").removeClass("d-none");
        };
    });

//Compter 

    $('#compter').on('click' , function(){
    //     var count = $("#liste li").length;
    //     $('#total').html("Voici vos tâches " + " " + count);
    
        var compte = 0;
        $('.hugo').each(function() {
            compte++;
        });
        $('#total').html(compte);
    });

});
