<?php

namespace PE\Bundle\UserBundle\Security;

class UsernameUserProvider extends AbstractUserProvider
{
    /**
     * @inheritDoc
     */
    protected function findUser($username)
    {
        return $this->userRepository->findUserByUsername($username);
    }
}