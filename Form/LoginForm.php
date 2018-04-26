<?php

namespace PE\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginForm extends AbstractType
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

        $builder->add('password', PasswordType::class, [
            'label'              => 'form.password',
            'translation_domain' => 'PEUser',
            'constraints'        => [new NotBlank()],
        ]);

        $builder->add('remember_me', CheckboxType::class, [
            'required'           => false,
            'label'              => 'form.remember_me',
            'label_attr'         => ['class' => 'checkbox-custom'],
            'translation_domain' => 'PEUser',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_token_id' => 'authenticate',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'pe_user__login';
    }
}