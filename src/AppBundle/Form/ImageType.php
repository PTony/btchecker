<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', FileType::class, array('label' => 'Image', 'required' => true))
            ->remove('uploadedAt', 'datetime')
            // ->add('gender')
            ->add('gender', ChoiceType::class, array(
                'choices'  => array(
                    'Homme' => 'Male',
                    'Femme' => 'Female',
                ),
                // *this line is important*
                'choices_as_values' => true,
                'expanded' => true,
                'multiple' => false,
            ))            
            ->remove('ownerId')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Image'
        ));
    }
}
