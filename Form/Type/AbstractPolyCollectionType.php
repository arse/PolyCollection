<?php
/**
 *
 * File: AbstractPolyCollectionType.php
 * User: thomas
 * Date: 20/11/12
 *
 */
namespace Infinite\PolyCollectionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractPolyCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ...

        $builder->add('_type', 'hidden', array(
                                              'data' => $this->getName(),
                                              'mapped' => false
                                         ));
    }

    public function getName(){
        return 'InifinitePolyCollectionAbstractType';
    }
}
