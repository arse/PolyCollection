$(function(){
    var polyCollectionCounter = 0;

    // when an available polytype line is clicked, add to list of current polytypes
    $('li.polycollection-type').click(function(){
        // pull data from elements
        var type = $(this).data('type') + '_' + polyCollectionCounter++;
        var itemPrototype = $(this).data('prototype');
        var liPrototype = $('ul.polycollection').data('prototype');

        // replace with a default title and insert li prototype into ul li wrapper prototype
        itemPrototype = itemPrototype.replace(/__name__/g, type);
        itemPrototype = itemPrototype.replace(/label__/g, type);
        var combinedPrototype = liPrototype.replace(/__ITEM__/, itemPrototype);
        combinedPrototype = combinedPrototype.replace(/__TITLE__/, type);

        // add to the list of polycollection
        $('ul.polycollection').append(combinedPrototype);
    });

    // allow items to be folded up
    $('.polycollection').on('click', '.polycollection-fold', function(){
        $('.polycollection-item-details', $(this).parent()).toggle("fold", {}, 200);
    });

    // allow items to be removed
    $('.polycollection').on('click', '.polycollection-remove', function(){
        $(this).parent('li').remove();
    });



});
