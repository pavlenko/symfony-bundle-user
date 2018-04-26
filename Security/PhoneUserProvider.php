<?php

namespace PE\Bundle\UserBundle\Security;

class PhoneUserProvider extends AbstractUserProvider
{
    /**
     * @inheritDoc
     */
    protected function findUser($username)
    {
        return $this->userRepository->findUserByPhone($username);
    }
}