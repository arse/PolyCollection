
Infinite PolyCollection Bundle (Symfony 2.1)
================================

Until Infinite release their form helper bundle of goodness, here's an unofficial bundle using Tim Nagel's "Poly
Collection" code which allows a form input to use a collection of different types (with a common parent) which could be
dynamically created at run time. I've made a few changes to the code, and added some template goodness and js.

* See longer description at http://tim.nagel.com.au/symfony2/forms/polycollection-for-symfony2-forms/
* See gist at https://gist.github.com/3058342 (for symfony 2.0)

Install
--------

1. Slap the code into a directory in either your src or vendor directory:
```
src/Infinite/PolyCollection
```
or
```
vendor/Infinite/PolyCollection
```

2. Enable in your appKernel.php
```
$bundles[] = new \Infinite\PolyCollectionBundle\InfinitePolyCollectionBundle();
```

Use
-----

1. All of your formtypes that are to be types in the poly collection must extend:
```
Infinite\PolyCollectionBundle\Form\Type\AbstractPolyCollectionType
```

2. For each formtype that is to be used inside the polycollection, register it in the container with an alias that is
the same as the form type name given in its getName method:
```
services:
    namespace.type.foo:
        class:    namespace\and\class\name
        tags:
            -   {name:form.type, alias: formtypename}
```

3. In the main form type class which is to make use of the poly collection add the polycollection type:
(replace "operator" with the name of the field you want the input to be for:
```
$builder->add('operator', 'InfinitePolyCollection', array(
        'types' => array(
            // type names to be used inside polycollection as defined by their getName()
            ...
        ),
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false
    ));
```

4. In your twig template which displays the main form type where you want the list of available types to display, you
need to pull in the template which lists the available types for you:
(Replace "form.operators" with the form variable name and the polycollection input name)
```
{% include 'InfinitePolyCollectionBundle::list_types.html.twig' with {'input': form.operators} %}
```

5. In the same template, for the polycollection input include the template for the polycollection input:
(again, replace "form.operators" with the form variable name, and the name of the polycollection input) also, add any
classes you want to be applied to the input
```
    <div>
        {% include 'InfinitePolyCollectionBundle::polycollection_input.html.twig' with {'input': form.operators [, 'class': 'any_additional_css_classes']}%}
    </div>
```

6. Be sure to install the assets and include the polycollection javascript and css in your template: (I find it useful to have
an additional javascripts and css block in my base templates)
```
{% block javascripts %}
    {% javascripts '@InfinitePolyCollectionBundle/Resources/public/polycollection.js' %}
        <script type="text/javascript" src="{{ asset_url }}"></script>
    {% endjavascripts %}
{% endblock %}
```
```
{% block stylesheets %}
    {% stylesheets 'bundles/infinitepolycollection/polycollection.css' %}
        <link type="text/css" rel="stylesheet" href="{{ asset_url}}" />
    {% endstylesheets %}
{% endblock %}
```

TODO
-----

* Add some CSS love to the form, esp the fold and remove divs
* Allow remove / fold to be optional in the form definition - so that instead of hardcoding it in the twig template,
before it renders the fold and remove div, it'll check to see if you've defined it in the form input definition
* How to translate the labels for the 'Add FormTypeName' li's into something more friendlier
