<?php

namespace PE\Bundle\UserBundle\Form\DataTransformer;

use PE\Bundle\UserBundle\Model\UserInterface;
use PE\Bundle\UserBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;

class User2PhoneTransformer implements DataTransformerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof UserInterface) {
            $value = $value->getPhone();
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (is_string($value) && $user = $this->userRepository->findUserByPhone($value)) {
            $value = $user;
        }

        return $value;
    }
}