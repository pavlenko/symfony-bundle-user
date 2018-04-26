<?php

namespace PE\Bundle\UserBundle\Security;

class EmailUserProvider extends AbstractUserProvider
{
    /**
     * @inheritDoc
     */
    protected function findUser($username)
    {
        return $this->userRepository->findUserByEmail($username);
    }
}