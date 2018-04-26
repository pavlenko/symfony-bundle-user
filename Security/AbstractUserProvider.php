<?php

namespace PE\Bundle\UserBundle\Security;

use PE\Bundle\UserBundle\Model\UserInterface;
use PE\Bundle\UserBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

abstract class AbstractUserProvider implements UserProviderInterface
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var string
     */
    private $class;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param string                  $class
     */
    public function __construct(UserRepositoryInterface $userRepository, $class)
    {
        $this->userRepository = $userRepository;
        $this->class          = $class;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername($username)
    {
        $user = $this->findUser($username);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        $class = get_class($user);

        if (!($user instanceof UserInterface) || !$this->supportsClass($class)) {
            throw new UnsupportedUserException("Expected an instance of {$this->class}, but got {$class}.");
        }

        if (null === $reloadedUser = $this->userRepository->findUserByID(array('id' => $user->getId()))) {
            throw new UsernameNotFoundException(sprintf(
                'User with ID "%s" could not be reloaded.',
                $user->getId()
            ));
        }

        return $reloadedUser;
    }

    /**
     * @inheritDoc
     */
    public function supportsClass($class)
    {
        return $this->class === $class || is_subclass_of($class, $this->class);
    }

    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    abstract protected function findUser($username);
}