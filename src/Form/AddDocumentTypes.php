<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
// use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Component\Validator\Constraints as Assert;

class AddDocumentTypes extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title'      , TextType::class    , ['constraints' => [new NotBlank()]])
            ->add('description', TextareaType::class, ['constraints' => []])
            ->add('category'   , ChoiceType::class  , ['choices' => Document::TYPES])
            /*
            ->add('file', FileType::class, [
                'multiple'    => false,
                'mapped'      => false,
                'constraints' => [new Assert\All(new Assert\File(Document::FILE_FIELD))]
            ])
            */
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
                'attr'  => ['class' => 'btn btn-primary']
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class
        ]);
    }
}
