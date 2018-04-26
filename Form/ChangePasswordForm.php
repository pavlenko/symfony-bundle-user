<?php

namespace PE\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordForm extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('currentPassword', PasswordType::class, [
            'mapped'             => false,
            'label'              => 'form.current_password',
            'translation_domain' => 'PEUser',
            'constraints'        => [
                new NotBlank(),
                new UserPassword(),
            ],
        ]);

        $builder->add('plainPassword', RepeatedType::class, [
            'type'            => PasswordType::class,
            'options'         => ['translation_domain' => 'PEUser'],
            'first_options'   => ['label' => 'form.new_password'],
            'second_options'  => ['label' => 'form.new_password_confirmation'],
            'invalid_message' => 'pe_user.password.mismatch',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix(): string
    {
        return 'pe_user__change_password';
    }
}