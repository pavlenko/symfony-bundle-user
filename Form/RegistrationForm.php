<?php

namespace PE\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationForm extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, [
            'label'              => 'form.username',
            'translation_domain' => 'PEUser',
            'constraints'        => [new NotBlank()],
        ]);

        $builder->add('email', EmailType::class, [
            'label'              => 'form.email',
            'translation_domain' => 'PEUser',
        ]);

        $builder->add('phone', TelType::class, [
            'label'              => 'form.phone',
            'translation_domain' => 'PEUser',
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $validate = function ($object, ExecutionContextInterface $context) {
            if (empty($object['email']) && empty($object['phone'])) {
                $context->buildViolation('You must provide email and/or phone')
                    ->atPath('email')
                    ->addViolation()
                ;
            }
        };

        $resolver->setDefaults([
            'constraints' => [new Callback($validate)]
        ]);
    }
}