<?php

namespace PE\Bundle\UserBundle\Form\DataTransformer;

use PE\Bundle\UserBundle\Model\UserInterface;
use PE\Bundle\UserBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;

class User2EmailTransformer implements DataTransformerInterface
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
            $value = $value->getEmail();
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

        if (is_string($value) && $user = $this->userRepository->findUserByEmail($value)) {
            $value = $user;
        }

        return $value;
    }
}