<?php


namespace BlogBundle\Form;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UpdatesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content', TextareaType::class)
            ->add('category', ChoiceType::class,array('choices'=>array( ''=>'',
            'sport'=>'sport', 'entertainment'=>'entertainment')))
            ->add('publish_date', DateTimeType::class,array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'disabled'=>'disabled'))
            ->add('img', FileType::class, array('data_class'=>null, 'required'=>false))
            ->add('Submit', SubmitType::class);
    }/**
 * {@inheritdoc}
 */public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults(array(
        'data_class' => 'AppBundle\Entity\Updates'
    ));
}

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'BlogBundle_post';
    }


}
