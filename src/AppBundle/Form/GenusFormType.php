<?php

namespace AppBundle\Form;

use AppBundle\Entity\Genus;
use AppBundle\Entity\SubFamily;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenusFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            // или можно вместо EntityType::class указать null, тогда симфони сам укажет EntityType::class и 'class' => SubFamily::class
            ->add('subFamily', EntityType::class, [
                'class' => SubFamily::class,
                'placeholder' => 'Choose an option',
            ])
            ->add('speciesCount')
            ->add('funFact')
            ->add('isPublished', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ]
            ])
            ->add('firstDiscoveredAt');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Genus::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_genus_form_type';
    }
}
