<?php

namespace Claror\FeinaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImagenShowType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		
		
        $builder
            ->add('nombre')
            ->add('orden')
            ->add('slug','hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Claror\FeinaBundle\Entity\Imagen'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'claror_feinabundle_imagenshow';
    }
}
