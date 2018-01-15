<?php

namespace BookReviewBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('comment', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', array(
            'attr' => array('cols' => '100', 'rows' => '5'),
        ))->add('rating', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', array('attr' => array(
            'min' => '1',
            'max' => '5'
        )))->add('book');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BookReviewBundle\Entity\Review'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bookreviewbundle_review';
    }


}
