<?php

namespace PE\Bundle\UserBundle\Security;

class IDUserProvider extends AbstractUserProvider
{
    /**
     * @inheritDoc
     */
    protected function findUser($username)
    {
        return $this->userRepository->findUserByID($username);
    }
}