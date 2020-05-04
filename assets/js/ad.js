$(document).ready(function() {

    $('#add-image-button').click(function(){
        // Récupération du nombre de champs actuellement présents dans le formulaire
        const index = +$('#widgets-counter').val();

        // Récupération du prototype des entrés "images"
        const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

        // Injection de ce code afin d'ajouter une nouvelle définition d'image
        $('#ad_images').append(tmpl);

        $('#widgets-counter').val( index + 1);

        handleDeleteButtons();
    });

    function handleDeleteButtons(){
        $('button[data-action="delete"]').click(function(){
            const target = this.dataset.target;
            $(target).remove();
        });
    }

    function updateWidgetCounter(){
        $('#widgets-counter').val( $('#ad_images div.form-group').length );
    }

    handleDeleteButtons();
    updateWidgetCounter();

});