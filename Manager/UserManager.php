<?php

namespace PE\Bundle\UserBundle\Manager;

use PE\Bundle\UserBundle\Repository\UserRepositoryInterface;
use PE\Bundle\UserBundle\Repository\GroupRepositoryInterface;

class UserManager
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var GroupRepositoryInterface
     */
    private $groupRepository;

    /**
     * @param UserRepositoryInterface  $userRepository
     * @param GroupRepositoryInterface $groupRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, GroupRepositoryInterface $groupRepository)
    {
        $this->userRepository  = $userRepository;
        $this->groupRepository = $groupRepository;
    }

    public function flush()
    {
        $this->userRepository->flush();
        $this->groupRepository->flush();
    }
}