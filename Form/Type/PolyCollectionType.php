<?php
/**
 *
 * File: PolyCollectionType.php
 * User: thomas
 * Date: 20/11/12
 *
 */
namespace Infinite\PolyCollectionBundle\Form\Type;

use Infinite\PolyCollectionBundle\Form\EventListener\ResizePolyFormListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * A collection type that will take an array of other form types
 * to use for each of the classes in an inheritance tree.
 *
 * Allows you to have a collection of objects in a Doctrine Single
 * Inheritance strategy with different form types for each of
 * the classes in the tree.
 *
 * @author Tim Nagel <t.nagel@infinite.net.au>
 */
class PolyCollectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $prototypes = $this->buildPrototypes($builder, $options);
        if ($options['allow_add'] && $options['prototype']) {
            $builder->setAttribute('prototypes', $prototypes);
        }

        $resizeListener = new ResizePolyFormListener(
            $builder->getFormFactory(),
            $prototypes,
            $options['options'],
            $options['allow_add'],
            $options['allow_delete']
        );

        $builder->addEventSubscriber($resizeListener);
    }

    /**
     * Builds prototypes for each of the form types used for the collection.
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return array
     */
    protected function buildPrototypes(FormBuilderInterface $builder, array $options)
    {
        $prototypes = array();
        foreach ($options['types'] as $type) {
            $key = ($type instanceof FormTypeInterface) ?
                $type->getName() :
                $builder->getFormFactory()->getType($type)->getName();

            $prototype = $this->buildPrototype($builder, $options['prototype_name'], $type, $options['options'])->getForm();
            $prototypes[$key] = $prototype;
        }

        return $prototypes;
    }

    /**
     * Builds an individual prototype.
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $name
     * @param string|FormTypeInterface $type
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function buildPrototype(FormBuilderInterface $builder, $name, $type, array $options)
    {
        $prototype = $builder->create($name, $type, array_replace(array(
                                                                       'label' => $name . 'label__',
                                                                  ), $options));

        return $prototype;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['allow_add'] = $options['allow_add'];
        $view->vars['allow_delete'] = $options['allow_delete'];

        if ($form->getConfig()->hasAttribute('prototypes')) {
            $view->vars['prototypes'] = array_map(function (FormInterface $prototype) use ($view) {
                    return $prototype->createView($view);
                }, $form->getConfig()->getAttribute('prototypes'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->getConfig()->hasAttribute('prototypes')) {
            $multiparts = array_filter(
                $view->vars['prototypes'],
                function (FormView $prototype) {
                    return $prototype->vars['multipart'];
                }
            );

            if ($multiparts) {
                $view->vars['multipart'] = true;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                                    'allow_add' => false,
                                    'allow_delete' => false,
                                    'prototype' => true,
                                    'prototype_name' => '__name__',
                                    'types' => array(),
                                    'type_name' => '_type',
                                    'options' => array(),
                               ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'InfinitePolyCollection';
    }
}
