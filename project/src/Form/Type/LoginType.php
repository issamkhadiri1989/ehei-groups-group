<?php

namespace App\Form\Type;

use App\DTO\LoginRequest;

use App\Entity\Agency;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LoginRequest::class,
            'csrf_token_id' => 'connect',
            'csrf_field_name' => '_csrf_token',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('username', TextType::class, [])
            ->add('password', TextType::class, [])
            ->add('agency', EntityType::class, [
                'class' => Agency::class,
                'choice_label' => 'name',
            ]);
    }
}
