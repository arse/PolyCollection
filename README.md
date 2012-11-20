
Inifinite PolyCollection Bundle (Symfony 2.1)
================================

Until Infinite release their form helper bundle of goodness, here's an unofficial bundle using Tim Nagel's "Poly
Collection" code which allows forms to use related types to be added to the form dynamically and submitted to a form
at run time. I've made a minor few changes to the code, and added a template and some js for this bundle

See longer description at http://tim.nagel.com.au/symfony2/forms/polycollection-for-symfony2-forms/
See gist at https://gist.github.com/3058342 (for symfony 2.0)

Use
-----

1. All of your formtypes that are to be types in the poly collection must extend:
```
Infinite\PolyCollectionBundle\Form\Type\AbstractPolyCollectionType
```

2. Register each form type in the container with an alias that is the same as the form type name given in its getName
method

```
services:
    namespace.type.foo:
        class:    namespace\and\class\name
        tags:
            -   {name:form.type, alias: formtypename}
```

3. In the main form type class which is to make use the poly collection: (replace "operator" with the name of the field
you want the input to be for
```
$builder->add('operator', 'polycollection', array(
        'types' => array(
            'formtype_service_alias_name',        // the formtype name as defined in getName()
            'another_formtype_service_alias_name',
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

5. In the same template, for the polycollection input: (again, replace "form.operators" with the form variable name, and
the name of the polycollection input)

```
    <div>
        {{ form_label(form.operators) }}
        <ul class="polycollection" data-prototype="<li class='polycollection-item'><div class='operator-fold'>Fold</div><div class='operator-remove'>Remove</div>__ITEM__</div></li>"></ul>
    </div>
```

6. Include the polycollection javascript in your template: (I find it useful to have an additional javascripts block in
my base templates)
```
{% block javascripts %}
{% javascripts
        '@InfinitePolyCollectionBundle/Resources/public/js/polycollection.js'
    %}
<script type="text/javascript" src="{{ asset_url }}"></script>
{% endblock %}
```
