<?php

namespace PE\Bundle\UserBundle\Form;

use PE\Bundle\UserBundle\Form\DataTransformer\User2EmailTransformer;
use PE\Bundle\UserBundle\Form\DataTransformer\User2PhoneTransformer;
use PE\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class ResetPasswordRequestForm extends AbstractType
{
    /**
     * @var User2EmailTransformer
     */
    private $user2emailTransformer;

    /**
     * @var User2PhoneTransformer
     */
    private $user2phoneTransformer;

    /**
     * @param User2EmailTransformer $user2emailTransformer
     * @param User2PhoneTransformer $user2phoneTransformer
     */
    public function __construct(
        User2EmailTransformer $user2emailTransformer,
        User2PhoneTransformer $user2phoneTransformer
    ) {
        $this->user2emailTransformer = $user2emailTransformer;
        $this->user2phoneTransformer = $user2phoneTransformer;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $field = $builder->create('identity', TextType::class, [
            'constraints' => [
                new NotNull(),
                new Type(UserInterface::class),
            ],
        ]);

        $field->addModelTransformer($this->user2emailTransformer);
        $field->addModelTransformer($this->user2phoneTransformer);

        $builder->add($field);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'pe_user__identity';
    }
}