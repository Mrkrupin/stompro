<?php

namespace App\Form;

use App\Entity\Lot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, array(
                'label' => 'Изображение',
                'required' => false,
                'mapped' => false,
            ))
            ->add('title', TextType::class, array(
                'label' => 'Название',
                'attr' => [
                    'placeholder' => 'Введите название'
                ]
            ))
            ->add('content', TextareaType::class, array(
                'label' => 'Описание',
                'attr' => [
                    'placeholder' => 'Введите описание'
                ]
            ))
            ->add('rate', MoneyType::class, array(
                'label' => 'Величина ставки',
                'currency' => 'RUB'
            ))
            ->add('price', MoneyType::class, array(
                'label' => 'Цена моментального выйгрыша',
                'currency' => 'RUB'
            ))
            ->add('win_date', DateTimeType::class, array(
                'label' => 'Дата окончания аукциона',
                'widget' => 'choice',
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Сохранить',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ))
            ->add('delete', SubmitType::class, array(
                'label' => 'Удалить',
                'attr' => [
                    'class' => 'btn btn-danger'
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lot::class,
        ]);
    }
}
