$(function(){
    var polyCollectionCounter = 0;

    $('li.polycollection-types').click(function(){
        // pull data from elements
        var type = $(this).data('type');
        var itemPrototype = $(this).data('prototype');
        var liPrototype = $('ul.polycollection').data('prototype');

        // replace with a default title and insert li prototype into ul li wrapper prototype
        itemPrototype = itemPrototype.replace(/__name__label__/g, type + ' ' + polyCollectionCounter++);
        var combinedPrototype = liPrototype.replace(/__ITEM__/, itemPrototype);

        // add to the list of polycollection
        $('ul.polycollection').append(combinedPrototype);
    });
});
