$(document).ready(function () {
    //configure the datepicker for the venue
    $('.js-venue-datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'fr',
        daysOfWeekDisabled: '2',
        todayHighlight: true,
        startDate: "0",
        datesDisabled: ['01/05/2017', '01/11/2017', '25/12/2017',
            '01/05/2018', '01/11/2018', '25/12/2018',
            '01/05/2019', '01/11/2019', '25/12/2019'],
        endDate: '31/12/2019',
        autoclose: true
    });

    // Get the div with « data-prototype »
    var $container = $('.js-order-item-wrapper');

    // Create a counter
    var index = $container.find(':input').length;

    // Add a new form when clicking on button
    $('.js-item-add').click(function (e) {
        addCategory($container);
        e.preventDefault();
        return false;
    });

    $('.js-order-item-wrapper').on('click', '.js-remove-item', function()
    {
        $(this).closest('.js-order-item').remove();//remove element
        //optionally submit department form via AJAX call to persist the delete
        return false;//stop event
    });



    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addCategory($container);
    }

    // La fonction qui ajoute un formulaire CategoryType
    function addCategory($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var template = $container.attr('data-prototype')
            .replace(/__name__/g, index)
        ;

        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        //create datepicker
        $prototype.find('.js-birth-datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'fr',
            startDate: '01/01/1900',
            endDate: '-4y',
            autoclose: true
        });

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }
});